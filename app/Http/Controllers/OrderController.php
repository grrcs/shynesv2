<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Admin: Lihat semua order
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $orders = \App\Models\Order::with('user')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function myOrders()
    {
        // Pembeli: Lihat order sendiri
        $orders = \App\Models\Order::where('user_id', auth()->id())->latest()->paginate(10);
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

    public function store(Request $request)
    {
        // Validasi
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang belanja kosong!');
        }

        // Hitung Total
        $totalPrice = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Cek Stok Lagi
        foreach ($cartItems as $item) {
             if ($item->product->stock < $item->quantity) {
                 return back()->with('error', 'Stok produk ' . $item->product->title . ' tidak mencukupi!');
             }
        }

        // Buat Order
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'total_price' => $totalPrice,
            'status' => 'pending', // Bisa 'paid' jika asumsi QRIS langsung sukses
            'invoice_number' => 'INV-' . time() . '-' . auth()->id(),
        ]);

        // Pindahkan item ke OrderItem
        foreach ($cartItems as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->title,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ]);

            // Kurangi Stok
            $item->product->decrement('stock', $item->quantity);
        }

        // Kosongkan Keranjang
        auth()->user()->cartItems()->delete();

        return redirect()->route('orders.my')->with('success', 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi admin.');
    }

    public function show($id)
    {
        $order = \App\Models\Order::with(['items', 'user'])->findOrFail($id);

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

        $order = \App\Models\Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
