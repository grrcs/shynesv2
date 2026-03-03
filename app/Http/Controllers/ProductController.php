<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

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
        
        $banners = Banner::where('is_active', true)->latest()->get();

        return view('products.index', compact('products', 'banners'));
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
        $validatedData = $this->validate($request, [
            'image'               => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'product_video'       => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:20480', // 20MB max
            'title'               => 'required|min:3',
            'category_id'         => 'required|exists:categories,id',
            'price'               => 'required|numeric',
            'stock'               => 'required|numeric',
            'description'         => 'required|min:10',
            'status'              => 'required|in:active,inactive,sold_out',
            'weight'              => 'nullable|numeric',
            'link_shopee'         => 'nullable|url',
            'is_discount_active'  => 'nullable|boolean',
            'discount_price'      => 'nullable|numeric',
            'discount_limit'      => 'nullable|numeric',
        ]);

        $this->productService->createProduct(
            $validatedData, 
            $request->file('image'), 
            $request->file('additional_images'), 
            $request->file('product_video')
        );

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
        $validatedData = $this->validate($request, [
            'image'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'product_video'       => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:20480',
            'title'               => 'required|min:3',
            'category_id'         => 'required|exists:categories,id',
            'price'               => 'required|numeric',
            'stock'               => 'required|numeric',
            'description'         => 'required|min:10',
            'status'              => 'required|in:active,inactive,sold_out',
            'weight'              => 'nullable|numeric',
            'link_shopee'         => 'nullable|url',
            'is_discount_active'  => 'nullable|boolean',
            'discount_price'      => 'nullable|numeric',
            'discount_limit'      => 'nullable|numeric',
        ]);

        $product = Product::findOrFail($id);
        
        $this->productService->updateProduct(
            $product, 
            $validatedData, 
            $request->file('image'),
            $request->file('additional_images'),
            $request->file('product_video'),
            $request->input('deleted_media', [])
        );

        return redirect()->route('products.index')->with(['success' => 'Produk Berhasil Diupdate!']);
    }

    /**
     * destroy - Hapus produk
     */
    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $this->productService->deleteProduct($product);

        return redirect()->route('products.index')->with(['success' => 'Produk Dihapus!']);
    }
}
