<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerContract;
use App\Models\SellerProduct;
use Illuminate\Http\Request;

class SellerManagementController extends Controller
{
    /**
     * List all seller contracts
     */
    public function contracts()
    {
        $contracts = SellerContract::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.sellers.contracts', compact('contracts'));
    }

    /**
     * Show contract detail
     */
    public function showContract(SellerContract $contract)
    {
        $contract->load(['user', 'products']);
        return view('admin.sellers.contract-detail', compact('contract'));
    }

    /**
     * Approve a seller contract
     */
    public function approveContract(Request $request, SellerContract $contract)
    {
        $validated = $request->validate([
            'default_markup_percentage' => 'required|numeric|min:0|max:100',
            'admin_notes' => 'nullable|string',
        ]);

        $contract->update([
            'status' => 'approved',
            'default_markup_percentage' => $validated['default_markup_percentage'],
            'admin_notes' => $validated['admin_notes'],
            'approved_at' => now(),
        ]);

        // Upgrade user role to penjual
        $contract->user->update(['role' => 'penjual']);

        return redirect()->route('admin.sellers.contracts')
            ->with('success', 'Kontrak penjual berhasil disetujui.');
    }

    /**
     * Reject a seller contract
     */
    public function rejectContract(Request $request, SellerContract $contract)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:5',
        ]);

        $contract->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'rejected_at' => now(),
        ]);

        return redirect()->route('admin.sellers.contracts')
            ->with('success', 'Kontrak penjual ditolak.');
    }

    /**
     * List all seller product submissions
     */
    public function products(Request $request)
    {
        $query = SellerProduct::with(['sellerContract.user', 'category']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $sellerProducts = $query->latest()->paginate(20);

        return view('admin.sellers.products', compact('sellerProducts'));
    }

    /**
     * Show seller product detail for approval
     */
    public function showProduct(SellerProduct $sellerProduct)
    {
        $sellerProduct->load(['sellerContract.user', 'category']);
        return view('admin.sellers.product-detail', compact('sellerProduct'));
    }

    /**
     * Approve a seller product and create it in the main store
     */
    public function approveProduct(Request $request, SellerProduct $sellerProduct)
    {
        $validated = $request->validate([
            'markup_percentage' => 'required|numeric|min:0|max:100',
            'admin_notes' => 'nullable|string',
        ]);

        $markupPercentage = $validated['markup_percentage'];
        $finalPrice = $sellerProduct->base_price * (1 + $markupPercentage / 100);

        // Create actual product in main store
        $product = Product::create([
            'category_id' => $sellerProduct->category_id,
            'image' => $sellerProduct->image,
            'title' => $sellerProduct->title,
            'slug' => $sellerProduct->slug,
            'description' => $sellerProduct->description,
            'price' => (int) round($finalPrice),
            'stock' => $sellerProduct->stock,
            'weight' => $sellerProduct->weight,
            'status' => 'active',
            'is_discount_active' => false,
        ]);

        // Update seller product
        $sellerProduct->update([
            'status' => 'approved',
            'markup_percentage' => $markupPercentage,
            'final_price' => $finalPrice,
            'admin_notes' => $validated['admin_notes'],
            'product_id' => $product->id,
        ]);

        return redirect()->route('admin.sellers.products')
            ->with('success', "Produk '{$sellerProduct->title}' disetujui dan ditambahkan ke toko.");
    }

    /**
     * Reject a seller product
     */
    public function rejectProduct(Request $request, SellerProduct $sellerProduct)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:5',
        ]);

        $sellerProduct->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('admin.sellers.products')
            ->with('success', "Produk '{$sellerProduct->title}' ditolak.");
    }
}
