<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     */
    public function index(): View
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi!']);
            }
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Check if item already in cart
        $cartItem = CartItem::where('user_id', auth()->id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            // Check discount limit
            if ($product->is_discount_active && $product->discount_limit) {
                if (($cartItem->quantity + $request->quantity) > $product->discount_limit) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Melebihi limit promo produk (Max: '.$product->discount_limit.')']);
                    }
                    return back()->with('error', 'Melebihi limit promo produk (Max: '.$product->discount_limit.')');
                }
            }

            // Update quantity if total doesn't exceed stock
            if (($cartItem->quantity + $request->quantity) > $product->stock) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk penambahan ini!']);
                }
                return back()->with('error', 'Stok tidak mencukupi untuk penambahan ini!');
            }
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // Check discount limit for new item
            if ($product->is_discount_active && $product->discount_limit) {
                if ($request->quantity > $product->discount_limit) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Melebihi limit promo produk (Max: '.$product->discount_limit.')']);
                    }
                    return back()->with('error', 'Melebihi limit promo produk (Max: '.$product->discount_limit.')');
                }
            }

            CartItem::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
            ]);
        }

        $cartCount = CartItem::where('user_id', auth()->id())->sum('quantity');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Produk ditambahkan ke keranjang!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('user_id', auth()->id())->findOrFail($id);
        
        // Check stock
        if ($cartItem->product->stock < $request->quantity) {
             return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Check discount limit
        if ($cartItem->product->is_discount_active && $cartItem->product->discount_limit) {
            if ($request->quantity > $cartItem->product->discount_limit) {
                return back()->with('error', 'Melebihi limit promo produk (Max: '.$cartItem->product->discount_limit.')');
            }
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang diperbarui!');
    }

    /**
     * Remove item from cart.
     */
    public function destroy($id): RedirectResponse
    {
        $cartItem = CartItem::where('user_id', auth()->id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
