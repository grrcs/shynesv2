<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $banners = [
            [
                'title' => 'SHYNESS Vol. 1 Release',
                'image' => 'banners/shyness_vol_1.png',
                'link' => '/products/shyness-vol-1-jacket',
                'is_active' => true,
            ],
            [
                'title' => 'New Collection 2026',
                'image' => 'banners/details.png',
                'link' => '/products',
                'is_active' => true,
            ],
            [
                'title' => 'Premium Fabric Quality',
                'image' => 'banners/fabric.png',
                'link' => '/products/fabric-classic-jacket',
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::firstOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
