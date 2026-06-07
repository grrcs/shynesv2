<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $hoodie = Category::where('name', 'Hoodie')->first();
        $tshirt = Category::where('name', 'T-Shirt')->first();

        // Delete all existing products
        Product::query()->delete();

        $products = [
            [
                'category_id' => $hoodie->id,
                'image' => 'shns-hellcat-boxy-cut-hoodie.webp',
                'title' => "SHNS | 'Hellcat' Boxy Cut Hoodie",
                'slug' => 'shns-hellcat-boxy-cut-hoodie',
                'description' => '<p>Hoodie boxy cut dengan desain Hellcat eksklusif dari SHNS.</p><p><strong>Material:</strong> Premium fleece cotton<br><strong>Fit:</strong> Boxy cut<br><strong>Care:</strong> Machine wash cold</p>',
                'price' => 248000,
                'stock' => 50,
                'weight' => 450,
                'link_shopee' => 'https://shopee.co.id/shyness',
                'status' => 'active',
                'is_discount_active' => false,
            ],
            [
                'category_id' => $tshirt->id,
                'image' => 'sn-promise-oversize-tshirt.webp',
                'title' => 'SN | "Promise" Oversize T-shirt',
                'slug' => 'sn-promise-oversize-t-shirt',
                'description' => '<p>T-shirt oversize dengan desain "Promise" dari SN.</p><p><strong>Material:</strong> Combed cotton 24s<br><strong>Fit:</strong> Oversize<br><strong>Care:</strong> Machine wash</p>',
                'price' => 95000,
                'stock' => 50,
                'weight' => 200,
                'link_shopee' => 'https://shopee.co.id/shyness',
                'status' => 'active',
                'is_discount_active' => false,
            ],
            [
                'category_id' => $tshirt->id,
                'image' => 'shns-croptop-edition.webp',
                'title' => 'SHNS | Croptop Edition',
                'slug' => 'shns-croptop-edition',
                'description' => '<p>Croptop edition eksklusif dari SHNS. Desain trendy dan nyaman.</p><p><strong>Material:</strong> Cotton blend<br><strong>Fit:</strong> Crop<br><strong>Care:</strong> Machine wash</p>',
                'price' => 50000,
                'stock' => 50,
                'weight' => 150,
                'link_shopee' => 'https://shopee.co.id/shyness',
                'status' => 'active',
                'is_discount_active' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
