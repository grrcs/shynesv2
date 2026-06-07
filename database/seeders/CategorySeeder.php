<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = [
            [
                'name' => 'Jacket',
                'description' => 'Jacket premium SHYNESS',
            ],
            [
                'name' => 'Hoodie',
                'description' => 'Hoodie nyaman dan stylish',
            ],
            [
                'name' => 'T-Shirt',
                'description' => 'T-Shirt casual SHYNESS',
            ],
            [
                'name' => 'Accessories',
                'description' => 'Aksesoris SHYNESS',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
