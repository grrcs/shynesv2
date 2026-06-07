<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SellerContract;
use App\Models\SellerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    /**
     * Show seller dashboard / request form
     */
    public function index()
    {
        $contract = auth()->user()->sellerContract;
        $products = [];

        if ($contract && $contract->isApproved()) {
            $products = $contract->products()->latest()->paginate(10);
        }

        return view('seller.index', compact('contract', 'products'));
    }

    /**
     * Show form to request becoming a seller
     */
    public function requestForm()
    {
        $existingContract = auth()->user()->sellerContract;

        if ($existingContract) {
            return redirect()->route('seller.index')
                ->with('info', 'Anda sudah mengajukan permintaan sebagai penjual.');
        }

        return view('seller.request');
    }

    /**
     * Submit seller request
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_description' => 'required|string|min:20',
            'phone' => 'required|string|max:20',
        ]);

        $existingContract = auth()->user()->sellerContract;
        if ($existingContract) {
            return redirect()->route('seller.index')
                ->with('error', 'Anda sudah memiliki pengajuan.');
        }

        SellerContract::create([
            'user_id' => auth()->id(),
            'business_name' => $validated['business_name'],
            'business_description' => $validated['business_description'],
            'phone' => $validated['phone'],
            'status' => 'pending',
        ]);

        return redirect()->route('seller.index')
            ->with('success', 'Pengajuan berhasil dikirim! Tunggu persetujuan admin.');
    }

    /**
     * Show form to submit a product
     */
    public function createProduct()
    {
        $contract = auth()->user()->sellerContract;

        if (!$contract || !$contract->isApproved()) {
            return redirect()->route('seller.index')
                ->with('error', 'Kontrak Anda belum disetujui.');
        }

        $categories = Category::all();
        return view('seller.products.create', compact('categories', 'contract'));
    }

    /**
     * Store a new seller product submission
     */
    public function storeProduct(Request $request)
    {
        $contract = auth()->user()->sellerContract;

        if (!$contract || !$contract->isApproved()) {
            return redirect()->route('seller.index')
                ->with('error', 'Kontrak Anda belum disetujui.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
            'base_price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:1',
            'weight' => 'nullable|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        $imageName = basename($imagePath);

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        SellerProduct::create([
            'seller_contract_id' => $contract->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'image' => $imageName,
            'base_price' => $validated['base_price'],
            'markup_percentage' => $contract->default_markup_percentage,
            'final_price' => $validated['base_price'] * (1 + $contract->default_markup_percentage / 100),
            'stock' => $validated['stock'],
            'weight' => $validated['weight'],
            'status' => 'pending',
        ]);

        return redirect()->route('seller.index')
            ->with('success', 'Produk berhasil diajukan! Tunggu persetujuan admin.');
    }
}
