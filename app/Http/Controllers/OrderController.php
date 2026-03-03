<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CheckoutService;
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

    public function store(Request $request, CheckoutService $checkoutService)
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        // Validate payment option
        $validated = $request->validate([
            'payment_option_id' => 'required|exists:payment_options,id',
            'address_id' => 'required|exists:addresses,id',
        ]);
        
        try {
            $order = $checkoutService->processCheckout($cartItems, auth()->id(), $validated['payment_option_id'], $validated['address_id']);
            
            // Send notification to user
            $order->user->notify(new \App\Notifications\OrderCreatedNotification($order));
            
            return redirect()->route('orders.my')->with('success', 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi admin.');
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
            ->where('id', $id)
            ->where(function($query) {
                $query->where('user_id', auth()->id())
                      ->orWhere('users.role', 'admin');
            })
            ->firstOrFail();

        return view('orders.track', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = Order::with('user')->findOrFail($id);
        $oldStatus = $order->status;
        
        // Update status with history
        $order->updateStatus($validated['status'], $validated['notes']);

        // Send notification to user
        $order->user->notify(
            new \App\Notifications\OrderStatusUpdatedNotification(
                $order, 
                $oldStatus, 
                $validated['status'], 
                $validated['notes']
            )
        );

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
}
