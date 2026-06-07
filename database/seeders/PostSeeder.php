<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $jacket = Category::where('name', 'Jacket')->first();

        $posts = [
            [
                'category_id' => $jacket->id,
                'image' => 'posts/shyness_vol_1.png',
                'title' => 'SHYNESS Vol. 1: Perjalanan Dimulai',
                'content' => '<p>Kami bangga memperkenalkan koleksi pertama SHYNESS Vol. 1. Jacket eksklusif dengan desain minimalist yang mencerminkan identitas anak muda Indonesia.</p><p>Koleksi ini terinspirasi dari semangat keberanian untuk tampil beda dan percaya diri.</p>',
                'status' => 'publish',
            ],
            [
                'category_id' => $jacket->id,
                'image' => 'posts/details.png',
                'title' => 'Proses Pembuatan Artboard Jacket',
                'content' => '<p>Setiap Artboard Jacket dibuat dengan penuh ketelitian. Mulai dari pemilihan bahan hingga proses finishing, kami memastikan kualitas terbaik untuk setiap produk.</p>',
                'status' => 'publish',
            ],
            [
                'category_id' => $jacket->id,
                'image' => 'posts/fabric.png',
                'title' => 'Mengenal Premium Fabric Kami',
                'content' => '<p>Bahan fabric yang kami gunakan dipilih langsung dari supplier terbaik. Dengan tekstur yang nyaman dan daya tahan tinggi, Fabric Classic Jacket cocok untuk penggunaan sehari-hari.</p>',
                'status' => 'publish',
            ],
        ];

        foreach ($posts as $post) {
            Post::firstOrCreate(
                ['title' => $post['title']],
                $post
            );
        }
    }
}
