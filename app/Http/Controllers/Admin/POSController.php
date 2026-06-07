<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'variants')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        $categories = Category::all();
        $users = User::where('role', 'pembeli')->get();
        $paymentOptions = PaymentOption::where('is_active', true)->get();

        return view('admin.pos.index', compact('products', 'categories', 'users', 'paymentOptions'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category_id');

        $products = Product::with('category', 'variants')
            ->where('status', 'active')
            ->where('stock', '>', 0);

        if ($query) {
            $products->where('title', 'like', '%' . $query . '%');
        }

        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }

        return response()->json($products->latest()->get());
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');

        $users = User::where('role', 'pembeli')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('phone', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.product_variant_id' => 'nullable|integer|exists:product_variants,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_option_id' => 'required|exists:payment_options,id',
        ], [
            'items.required' => 'Keranjang tidak boleh kosong',
            'items.*.product_id.required' => 'Product ID wajib diisi',
            'items.*.quantity.required' => 'Quantity wajib diisi',
            'items.*.quantity.integer' => 'Quantity harus berupa angka',
            'customer_name.required' => 'Nama customer wajib diisi',
            'customer_phone.required' => 'Nomor telepon wajib diisi',
            'payment_option_id.required' => 'Metode pembayaran wajib dipilih',
        ]);

        $items = $request->input('items', []);
        $customerName = $request->input('customer_name');
        $customerPhone = $request->input('customer_phone');
        $customerAddress = $request->input('customer_address', '');
        $userId = $request->input('user_id');
        $paymentOptionId = $request->input('payment_option_id');
        $notes = $request->input('notes', '');

        return DB::transaction(function () use ($items, $customerName, $customerPhone, $customerAddress, $userId, $paymentOptionId, $notes) {

        $subtotal = 0;
        $orderItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $variant = null;
            if (!empty($item['product_variant_id'])) {
                $variant = \App\Models\ProductVariant::find($item['product_variant_id']);
            }

            // Use variant price if available, otherwise product price
            $price = $variant ? $variant->final_price : $product->price;
            if (!$variant && $product->is_discount_active && $product->discount_price > 0) {
                $price = $product->discount_price;
            }

            $quantity = (int) $item['quantity'];
            $itemTotal = $price * $quantity;

            $subtotal += $itemTotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $variant ? $variant->id : null,
                'product_name' => $product->title,
                'price' => $price,
                'quantity' => $quantity,
                'total' => $itemTotal
            ];

            // Kurangi stok
            if ($variant) {
                $variant->decrementStock($quantity);
            } else {
                $product->decrement('stock', $quantity);
            }
        }

        if (empty($orderItems)) {
            return response()->json(['success' => false, 'message' => 'Produk tidak valid'], 400);
        }

        // Generate invoice number
        $invoiceNumber = 'POS-' . date('Ymd') . '-' . Str::random(6);

        // Get payment option to determine payment type
        $paymentOption = PaymentOption::find($paymentOptionId);
        $paymentCode = $paymentOption->code ?? 'cash';
        
        // Determine order status based on payment type
        // Cash payments are completed immediately (after detection verification)
        // Digital payments (QRIS, VA) start as pending
        $orderStatus = in_array($paymentCode, ['cash', 'cod']) ? 'completed' : 'pending';

        // Buat pesanan
        // For POS: use selected customer or fallback to admin (cashier) user
        $orderUserId = $userId ?: Auth::id();
        
        $order = Order::create([
            'user_id' => $orderUserId,
            'payment_option_id' => $paymentOptionId,
            'total_price' => $subtotal,
            'status' => $orderStatus,
            'invoice_number' => $invoiceNumber,
            'payment_channel' => $paymentCode,
            'shipping_recipient_name' => $customerName,
            'shipping_phone_number' => $customerPhone,
            'shipping_address' => $customerAddress,
        ]);

        // Buat order items
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ]);
        }

        // Catat status history
        // Map 'completed' to 'delivered' for status history enum compatibility
        $historyStatus = $orderStatus === 'completed' ? 'delivered' : $orderStatus;
        $order->statusHistory()->create([
            'status' => $historyStatus,
            'notes' => 'Pesanan POS - ' . $notes,
            'changed_at' => now(),
        ]);

        // For digital payments (QRIS, VA, etc.), create payment via WijayaPayService
        $paymentUrl = null;
        $paymentToken = null;
        $paymentData = null;
        
        if (!in_array($paymentCode, ['cash', 'cod'])) {
            $wijayaPayService = app(\App\Services\WijayaPayService::class);
            $paymentResult = $wijayaPayService->createPayment($order, $paymentCode);
            
            if ($paymentResult['success']) {
                $paymentUrl = $paymentResult['payment_url'];
                $paymentToken = $paymentResult['payment_token'];
                $paymentData = $paymentResult['data'] ?? null;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat!',
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'total' => $subtotal,
            'status' => $orderStatus,
            'payment_url' => $paymentUrl,
            'payment_token' => $paymentToken,
            'payment_type' => $paymentCode,
            'payment_data' => $paymentData,
            'payment_debug' => isset($paymentResult) ? $paymentResult : null,
        ]);

        }); // end DB::transaction
    }

    public function getProductVariants($productId)
    {
        $product = Product::with('variants')->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product->variants);
    }

    public function getRecentTransactions()
    {
        $transactions = Order::with(['paymentOption', 'items'])
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'invoice_number' => $order->invoice_number,
                    'customer_name' => $order->shipping_recipient_name,
                    'customer_phone' => $order->shipping_phone_number,
                    'total_price' => $order->total_price,
                    'payment_option' => $order->paymentOption ? $order->paymentOption->name : 'N/A',
                    'items' => $order->items->map(function($item) {
                        return [
                            'product_name' => $item->display_name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'total' => $item->price * $item->quantity
                        ];
                    })->toArray(),
                    'created_at' => $order->created_at->format('H:i'),
                ];
            });

        return response()->json($transactions);
    }

    public function getTransactionDetail($orderId)
    {
        $order = Order::with(['paymentOption', 'items'])->find($orderId);
        if (!$order) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'customer_name' => $order->shipping_recipient_name,
            'customer_phone' => $order->shipping_phone_number,
            'customer_address' => $order->shipping_address,
            'total_price' => $order->total_price,
            'payment_option' => $order->paymentOption ? $order->paymentOption->name : 'N/A',
            'notes' => $order->statusHistory->first()->notes ?? '',
            'items' => $order->items->map(function($item) {
                return [
                    'product_name' => $item->display_name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->price * $item->quantity
                ];
            })->toArray(),
            'created_at' => $order->created_at->format('d/m/Y H:i'),
        ]);
    }

    public function getSalesReport(Request $request)
    {
        $type = $request->get('type', 'daily');
        
        if ($type === 'weekly') {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
            
            $data = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $orders = Order::where('status', 'completed')
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->get();
                
                $data->push([
                    'date' => $date->format('d/m'),
                    'day_name' => $date->format('D'),
                    'total_orders' => $orders->count(),
                    'total_revenue' => $orders->sum('total_price')
                ]);
            }
            
            return response()->json([
                'type' => 'weekly',
                'data' => $data,
                'total_revenue' => $data->sum('total_revenue'),
                'total_orders' => $data->sum('total_orders')
            ]);
        }
        
        // Daily
        $todayOrders = Order::where('status', 'completed')
            ->whereDate('created_at', today())
            ->get();
            
        return response()->json([
            'type' => 'daily',
            'total_orders' => $todayOrders->count(),
            'total_revenue' => $todayOrders->sum('total_price')
        ]);
    }

    public function checkPaymentStatus($orderId)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // For cash/cod, check if status is completed
        if (in_array($order->payment_channel, ['cash', 'cod'])) {
            return response()->json([
                'success' => true,
                'status' => $order->status === 'completed' ? 'paid' : 'pending',
                'paid_at' => $order->status === 'completed' ? $order->updated_at : null
            ]);
        }

        // For digital payments (QRIS, VA), check via WijayaPayService
        if ($order->payment_token) {
            try {
                $wijayaPayService = app(\App\Services\WijayaPayService::class);
                $statusResult = $wijayaPayService->checkPaymentStatus($order->invoice_number);
                
                if ($statusResult['success'] && $statusResult['status'] === 'SUCCESS') {
                    // Update order status if paid
                    if ($order->status !== 'paid' && $order->status !== 'completed') {
                        $order->update(['status' => 'paid']);
                    }
                    
                    return response()->json([
                        'success' => true,
                        'status' => 'paid',
                        'paid_at' => $statusResult['paid_at'] ?? now()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'paid_at' => null
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to check payment status: ' . $e->getMessage()
                ], 500);
            }
        }

        // Default response
        return response()->json([
            'success' => true,
            'status' => $order->status === 'paid' || $order->status === 'completed' ? 'paid' : 'pending',
            'paid_at' => $order->updated_at
        ]);
    }
}
