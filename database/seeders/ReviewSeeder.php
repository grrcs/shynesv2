<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pembeli = User::where('email', 'pembeli@gmail.com')->first();
        $admin = User::where('email', 'admin@gmail.com')->first();
        $product = Product::first();

        if ($pembeli && $product) {
            Review::firstOrCreate(
                [
                    'user_id' => $pembeli->id,
                    'product_id' => $product->id,
                ],
                [
                    'rating' => 5,
                    'comment' => 'Jacket-nya sesuai banget sama ekspektasi. Bahannya adem dan nyaman dipakai. Jahitannya juga rapi. Seller ramah!',
                ]
            );
        }

        if ($admin && $product) {
            Review::firstOrCreate(
                [
                    'user_id' => $admin->id,
                    'product_id' => $product->id,
                ],
                [
                    'rating' => 5,
                    'comment' => 'Produk premium dengan harga terjangkau. Highly recommended untuk anak muda yang cari jacket stylish!',
                ]
            );
        }
    }
}
