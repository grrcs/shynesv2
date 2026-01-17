<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * index - Menampilkan daftar produk untuk Admin
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = Product::with('category')->latest();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $products = $query->paginate(10);
        $products->appends(['search' => $search]);

        return view('products.index', compact('products'));
    }

    /**
     * show - Menampilkan detail produk
     */
    public function show($id): View
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * create - Form tambah produk
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * store - Simpan produk baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title'       => 'required|min:3',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'description' => 'required|min:10',
            'status'      => 'required|in:active,inactive,sold_out'
        ]);

        // Upload Gambar
        $image = $request->file('image');
        $image->storeAs('products', $image->hashName(), 'public');

        Product::create([
            'image'       => $image->hashName(),
            'title'       => $request->title,
            'slug'        => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'weight'      => $request->weight ?? 100, // Default 100gr
            'link_shopee' => $request->link_shopee,
            'status'      => $request->status
        ]);

        return redirect()->route('products.index')->with(['success' => 'Produk Berhasil Ditambahkan!']);
    }

    /**
     * edit - Form edit produk
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * update - Update data produk
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'title'       => 'required|min:3',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'description' => 'required|min:10',
            'status'      => 'required|in:active,inactive,sold_out'
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('products', $image->hashName(), 'public');
            Storage::disk('public')->delete('products/'.$product->image);

            $product->update([
                'image'       => $image->hashName(),
                'title'       => $request->title,
                'slug'        => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'weight'      => $request->weight,
                'link_shopee' => $request->link_shopee,
                'status'      => $request->status
            ]);
        } else {
            $product->update([
                'title'       => $request->title,
                'slug'        => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'weight'      => $request->weight,
                'link_shopee' => $request->link_shopee,
                'status'      => $request->status
            ]);
        }

        return redirect()->route('products.index')->with(['success' => 'Produk Berhasil Diupdate!']);
    }

    /**
     * destroy - Hapus produk
     */
    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        Storage::disk('public')->delete('products/'.$product->image);
        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Produk Dihapus!']);
    }
}
