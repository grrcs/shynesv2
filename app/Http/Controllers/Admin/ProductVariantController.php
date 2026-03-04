<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->paginate(10);
        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:product_variants',
            'is_active' => 'boolean',
        ]);

        $variant = $product->variants()->create($validated);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Varian produk berhasil ditambahkan!');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:product_variants,sku,' . $variant->id,
            'is_active' => 'boolean',
        ]);

        $variant->update($validated);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Varian produk berhasil diperbarui!');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Varian produk berhasil dihapus!');
    }

    public function bulkUpdateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:product_variants,id',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        foreach ($validated['variants'] as $variantData) {
            $variant = $product->variants()->find($variantData['id']);
            if ($variant) {
                $variant->update(['stock' => $variantData['stock']]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Stok berhasil diperbarui!']);
    }
}
