<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CheckoutService;
use App\Services\PakasirService;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        // Admin: Lihat semua order
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $orders = Order::with('user')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function myOrders()
    {
        // Pembeli: Lihat order sendiri
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        return view('orders.my_orders', compact('orders'));
    }

    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja kosong!');
        }
        
        $addresses = auth()->user()->addresses()->orderByDesc('is_primary')->latest()->get();
        
        // Get active payment options
        $paymentOptions = \App\Models\PaymentOption::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('checkout.payment-options', compact('cartItems', 'paymentOptions', 'addresses'));
    }

    public function store(Request $request, CheckoutService $checkoutService, PakasirService $pakasirService)
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        $validated = $request->validate([
            'payment_option_id' => 'required|exists:payment_options,id',
            'address_id' => 'required|exists:addresses,id',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);
        
        try {
            $order = $checkoutService->processCheckout(
                $cartItems, 
                auth()->id(), 
                $validated['payment_option_id'], 
                $validated['address_id'],
                $validated['coupon_code'] ?? null
            );
            
            $order->user->notify(new \App\Notifications\OrderCreatedNotification($order));
            
            return $this->processPayment($order, $pakasirService);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $order = Order::with(['items', 'user', 'statusHistory', 'shippingDetail', 'paymentOption'])->findOrFail($id);

        // Cek akses: Admin atau Pemilik Order
        if (auth()->user()->role !== 'admin' && auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.show', compact('order'));
    }

    public function track($id)
    {
        $order = Order::with(['statusHistory', 'shippingDetail', 'items.product'])
            ->findOrFail($id);

        // Cek akses: Admin atau Pemilik Order
        if (auth()->user()->role !== 'admin' && auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.track', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,confirmed,processing,shipped,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = Order::with('user')->findOrFail($id);
        $oldStatus = $order->status;
        
        $notes = $validated['notes'] ?? null;

        // Update status with history
        $order->updateStatus($validated['status'], $notes);

        // Send notification to user
        $order->user->notify(
            new \App\Notifications\OrderStatusUpdatedNotification(
                $order, 
                $oldStatus, 
                $validated['status'], 
                $notes
            )
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status pesanan diperbarui.']);
        }

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function updateShipping(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'courier_name' => 'required|string|max:100',
            'service_type' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipped_at' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
        ]);

        $order = Order::findOrFail($id);
        
        // Create or update shipping details
        $shippingDetail = $order->shippingDetail ?? new \App\Models\ShippingDetail();
        $shippingDetail->fill($validated);
        $shippingDetail->order_id = $order->id;
        $shippingDetail->save();

        // If tracking number is added and status is still processing, update to shipped
        if ($validated['tracking_number'] && $order->status === 'processing') {
            $order->updateStatus('shipped', 'Pesanan telah dikirim dengan nomor resi: ' . $validated['tracking_number']);
        }

        return back()->with('success', 'Detail pengiriman diperbarui.');
    }

    public function checkCourierStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $order = Order::with('shippingDetail')->findOrFail($id);
        $shippingDetail = $order->shippingDetail;

        if (!$shippingDetail || !$shippingDetail->tracking_number) {
            return response()->json(['error' => 'Nomor resi tidak tersedia'], 400);
        }

        // In real implementation, integrate with courier API
        // For now, return mock data
        $courier = strtolower($shippingDetail->courier_name);
        $trackingNumber = $shippingDetail->tracking_number;

        // Mock API response (replace with real API integration)
        $mockStatus = [
            'jne' => [
                'status' => 'in_transit',
                'description' => 'Paket dalam perjalanan',
                'location' => 'Jakarta',
                'timestamp' => now()->subHours(2)->toIso8601String(),
            ],
            'j&t' => [
                'status' => 'picked_up',
                'description' => 'Paket telah diambil',
                'location' => 'Surabaya',
                'timestamp' => now()->subHours(4)->toIso8601String(),
            ],
            'sicepat' => [
                'status' => 'delivered',
                'description' => 'Paket telah dikirim',
                'location' => 'Bandung',
                'timestamp' => now()->subHours(1)->toIso8601String(),
            ],
        ];

        $status = $mockStatus[$courier] ?? [
            'status' => 'unknown',
            'description' => 'Status tidak diketahui',
            'location' => 'Unknown',
            'timestamp' => now()->toIso8601String(),
        ];

        return response()->json([
            'courier' => $shippingDetail->courier_name,
            'tracking_number' => $trackingNumber,
            'status' => $status,
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon->isValid()) {
            return response()->json(['error' => 'Kupon tidak valid atau telah kedaluwarsa'], 422);
        }

        if (!$coupon->isValidForUser(auth()->id())) {
            return response()->json(['error' => 'Anda telah mencapai batas penggunaan kupon ini'], 422);
        }

        if (!$coupon->isValidForOrder($request->subtotal)) {
            return response()->json([
                'error' => 'Minimum order Rp ' . number_format($coupon->minimum_order_amount, 0, ',', '.') . ' diperlukan untuk kupon ini'
            ], 422);
        }

        $discountAmount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'success' => true,
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'discount_amount' => $discountAmount,
                'formatted_discount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
            ],
        ]);
    }

    public function directBuyPage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('category')->findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        $addresses = auth()->user()->addresses()->orderByDesc('is_primary')->latest()->get();
        $paymentOptions = \App\Models\PaymentOption::where('is_active', true)->orderBy('name')->get();

        $priceToUse = ($product->is_discount_active && $product->discount_price) ? $product->discount_price : $product->price;
        $subtotal = $priceToUse * $request->quantity;

        return view('checkout.direct-buy', compact('product', 'addresses', 'paymentOptions', 'subtotal', 'priceToUse'));
    }

    public function storeDirectBuy(Request $request, CheckoutService $checkoutService, PakasirService $pakasirService)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_option_id' => 'required|exists:payment_options,id',
            'address_id' => 'required|exists:addresses,id',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        try {
            $priceToUse = ($product->is_discount_active && $product->discount_price) ? $product->discount_price : $product->price;

            $order = $checkoutService->processDirectBuy(
                $product,
                $request->quantity,
                $priceToUse,
                auth()->id(),
                $validated['payment_option_id'],
                $validated['address_id'],
                $validated['coupon_code'] ?? null
            );

            $order->user->notify(new \App\Notifications\OrderCreatedNotification($order));

            return $this->processPayment($order, $pakasirService);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function processPayment($order, PakasirService $pakasirService)
    {
        $nonGatewayCodes = ['cash', 'cod', 'bank_transfer', 'credit_card', 'ewallet'];
        $paymentCode = $order->paymentOption->code ?? null;

        if ($paymentCode && !in_array($paymentCode, $nonGatewayCodes)) {
            $method = $this->toPakasirMethod($paymentCode);
            $paymentResult = $pakasirService->createTransaction(
                $order->invoice_number,
                (int) $order->total_price,
                $method
            );

            if ($paymentResult['success']) {
                $order->update([
                    'payment_channel' => $paymentCode,
                    'payment_token' => $paymentResult['payment_number'] ?? null,
                    'payment_url' => $paymentResult['payment_number'] ?? null,
                ]);

                return redirect()->route('payment.pakasir.waiting', $order->id)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
            }

            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Pesanan dibuat, tapi gagal membuat pembayaran: ' . ($paymentResult['message'] ?? 'Unknown error'));
        }

        return redirect()->route('orders.my')->with('success', 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi admin.');
    }

    private function toPakasirMethod(string $code): string
    {
        return match (strtoupper($code)) {
            'QRIS' => 'qris',
            'BRIVA' => 'bri_va',
            'BCAVA' => 'bni_va',
            'BNIVA' => 'bni_va',
            'MANDIRIVA' => 'permata_va',
            'BSIVA' => 'bni_va',
            'CIMBVA' => 'cimb_niaga_va',
            'PERMATAVA' => 'permata_va',
            'MAYBANKVA' => 'maybank_va',
            'BNCVA' => 'bnc_va',
            'SAMPOERNAVA' => 'sampoerna_va',
            'ATMBERSAMAVA' => 'atm_bersama_va',
            'ARTHAGRAHAVA' => 'artha_graha_va',
            default => 'qris',
        };
    }
}
