<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product.
     */
    public function createProduct(array $data, $imageFile, $additionalImages = [], $videoFile = null): Product
    {
        $imageName = $imageFile->hashName();
        $imageFile->storeAs('products', $imageName, 'public');

        $product = Product::create([
            'image'       => $imageName,
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title'], '-'),
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'weight'      => $data['weight'] ?? 100,
            'link_shopee' => $data['link_shopee'] ?? null,
            'status'      => $data['status'],
            'is_discount_active' => $data['is_discount_active'] ?? false,
            'discount_price'     => $data['discount_price'] ?? null,
            'discount_limit'     => $data['discount_limit'] ?? null,
        ]);

        if ($additionalImages) {
            foreach ($additionalImages as $additionalImage) {
                $addName = $additionalImage->hashName();
                $additionalImage->storeAs('products', $addName, 'public');
                $product->media()->create([
                    'file_path' => $addName,
                    'file_type' => 'image'
                ]);
            }
        }

        if ($videoFile) {
            $videoName = $videoFile->hashName();
            $videoFile->storeAs('products_video', $videoName, 'public');
            $product->media()->create([
                'file_path' => $videoName,
                'file_type' => 'video'
            ]);
        }

        return $product;
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data, $imageFile = null, $additionalImages = [], $videoFile = null, array $deletedMedia = []): Product
    {
        $updateData = [
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title'], '-'),
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'weight'      => $data['weight'] ?? $product->weight,
            'link_shopee' => $data['link_shopee'] ?? $product->link_shopee,
            'status'      => $data['status'],
            'is_discount_active' => $data['is_discount_active'] ?? false,
            'discount_price'     => $data['discount_price'] ?? null,
            'discount_limit'     => $data['discount_limit'] ?? null,
        ];

        if ($imageFile) {
            $imageName = $imageFile->hashName();
            $imageFile->storeAs('products', $imageName, 'public');
            
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            
            $updateData['image'] = $imageName;
        }

        $product->update($updateData);

        if ($additionalImages) {
            foreach ($additionalImages as $additionalImage) {
                $addName = $additionalImage->hashName();
                $additionalImage->storeAs('products', $addName, 'public');
                $product->media()->create([
                    'file_path' => $addName,
                    'file_type' => 'image'
                ]);
            }
        }

        if ($videoFile) {
            $videoName = $videoFile->hashName();
            $videoFile->storeAs('products_video', $videoName, 'public');
            $product->media()->create([
                'file_path' => $videoName,
                'file_type' => 'video'
            ]);
        }

        if (!empty($deletedMedia)) {
            $mediaToDelete = \App\Models\ProductMedia::whereIn('id', $deletedMedia)->where('product_id', $product->id)->get();
            foreach ($mediaToDelete as $media) {
                if ($media->file_type === 'video') {
                    Storage::disk('public')->delete('products_video/' . $media->file_path);
                } else {
                    Storage::disk('public')->delete('products/' . $media->file_path);
                }
                $media->delete();
            }
        }

        return $product;
    }

    /**
     * Delete a product and its image.
     */
    public function deleteProduct(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete('products/' . $product->image);
        }
        $product->delete();
    }
}
