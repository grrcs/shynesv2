<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductMedia;
use App\Models\Product;

class ProductMediaSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $product1 = Product::where('slug', 'shyness-vol-1-jacket')->first();

        if ($product1) {
            ProductMedia::firstOrCreate(
                ['product_id' => $product1->id, 'file_path' => 'products/details.png'],
                ['file_type' => 'image']
            );
            ProductMedia::firstOrCreate(
                ['product_id' => $product1->id, 'file_path' => 'products/fabric.png'],
                ['file_type' => 'image']
            );
        }

        $product2 = Product::where('slug', 'artboard-jacket-premium')->first();

        if ($product2) {
            ProductMedia::firstOrCreate(
                ['product_id' => $product2->id, 'file_path' => 'products/shyness_vol_1.png'],
                ['file_type' => 'image']
            );
        }

        $product3 = Product::where('slug', 'fabric-classic-jacket')->first();

        if ($product3) {
            ProductMedia::firstOrCreate(
                ['product_id' => $product3->id, 'file_path' => 'products/details.png'],
                ['file_type' => 'image']
            );
        }
    }
}
