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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', auth()->id())
                          ->where('product_id', $request->product_id)
                          ->exists();

        if ($exists) {
            return back()->with('info', 'Produk sudah ada di wishlist!');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist!');
    }

    public function destroy($id): RedirectResponse
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->findOrFail($id);
        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
