<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Product;

class ProductVariantSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $product1 = Product::where('slug', 'shyness-vol-1-jacket')->first();
        $product2 = Product::where('slug', 'artboard-jacket-premium')->first();
        $product3 = Product::where('slug', 'fabric-classic-jacket')->first();

        if ($product1) {
            $variants = [
                ['product_id' => $product1->id, 'size' => 'S', 'color' => 'Black', 'price' => 499000, 'stock' => 5, 'sku' => 'SHY-V1-S-BLK'],
                ['product_id' => $product1->id, 'size' => 'S', 'color' => 'White', 'price' => 499000, 'stock' => 5, 'sku' => 'SHY-V1-S-WHT'],
                ['product_id' => $product1->id, 'size' => 'M', 'color' => 'Black', 'price' => 499000, 'stock' => 8, 'sku' => 'SHY-V1-M-BLK'],
                ['product_id' => $product1->id, 'size' => 'M', 'color' => 'White', 'price' => 499000, 'stock' => 7, 'sku' => 'SHY-V1-M-WHT'],
                ['product_id' => $product1->id, 'size' => 'L', 'color' => 'Black', 'price' => 499000, 'stock' => 5, 'sku' => 'SHY-V1-L-BLK'],
                ['product_id' => $product1->id, 'size' => 'L', 'color' => 'White', 'price' => 499000, 'stock' => 5, 'sku' => 'SHY-V1-L-WHT'],
                ['product_id' => $product1->id, 'size' => 'XL', 'color' => 'Black', 'price' => 499000, 'stock' => 3, 'sku' => 'SHY-V1-XL-BLK'],
                ['product_id' => $product1->id, 'size' => 'XL', 'color' => 'White', 'price' => 499000, 'stock' => 3, 'sku' => 'SHY-V1-XL-WHT'],
            ];

            foreach ($variants as $variant) {
                ProductVariant::firstOrCreate(
                    ['sku' => $variant['sku']],
                    $variant
                );
            }
        }

        if ($product2) {
            $variants = [
                ['product_id' => $product2->id, 'size' => 'S', 'color' => 'Navy', 'price' => 599000, 'stock' => 4, 'sku' => 'ART-S-NVY'],
                ['product_id' => $product2->id, 'size' => 'M', 'color' => 'Navy', 'price' => 599000, 'stock' => 5, 'sku' => 'ART-M-NVY'],
                ['product_id' => $product2->id, 'size' => 'L', 'color' => 'Navy', 'price' => 599000, 'stock' => 4, 'sku' => 'ART-L-NVY'],
                ['product_id' => $product2->id, 'size' => 'XL', 'color' => 'Navy', 'price' => 599000, 'stock' => 2, 'sku' => 'ART-XL-NVY'],
            ];

            foreach ($variants as $variant) {
                ProductVariant::firstOrCreate(
                    ['sku' => $variant['sku']],
                    $variant
                );
            }
        }

        if ($product3) {
            $variants = [
                ['product_id' => $product3->id, 'size' => 'S', 'color' => 'Charcoal', 'price' => 699000, 'stock' => 5, 'sku' => 'FAB-S-CHR'],
                ['product_id' => $product3->id, 'size' => 'M', 'color' => 'Charcoal', 'price' => 699000, 'stock' => 6, 'sku' => 'FAB-M-CHR'],
                ['product_id' => $product3->id, 'size' => 'L', 'color' => 'Charcoal', 'price' => 699000, 'stock' => 5, 'sku' => 'FAB-L-CHR'],
                ['product_id' => $product3->id, 'size' => 'XL', 'color' => 'Charcoal', 'price' => 699000, 'stock' => 4, 'sku' => 'FAB-XL-CHR'],
            ];

            foreach ($variants as $variant) {
                ProductVariant::firstOrCreate(
                    ['sku' => $variant['sku']],
                    $variant
                );
            }
        }
    }
}
