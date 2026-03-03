<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = auth()->user()->wishlist()->with('product')->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::where('user_id', auth()->id())
                          ->where('product_id', $request->product_id)
                          ->first();

        if ($wishlist) {
            $wishlist->delete();
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'action' => 'removed', 'message' => 'Produk dihapus dari wishlist.']);
            }
            return back()->with('info', 'Produk dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'action' => 'added', 'message' => 'Produk ditambahkan ke wishlist!']);
        }
        return back()->with('success', 'Produk ditambahkan ke wishlist!');
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->findOrFail($id);
        $wishlist->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk dihapus dari wishlist.']);
        }
        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
