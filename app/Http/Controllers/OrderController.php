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
        return view('checkout.payment', compact('cartItems'));
    }

    public function store(Request $request, CheckoutService $checkoutService)
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        try {
            $checkoutService->processCheckout($cartItems, auth()->id());
            return redirect()->route('orders.my')->with('success', 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi admin.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $order = Order::with(['items', 'user'])->findOrFail($id);

        // Cek akses: Admin atau Pemilik Order
        if (auth()->user()->role !== 'admin' && auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
