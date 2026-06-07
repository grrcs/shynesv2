<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $videos = [
            [
                'title' => 'SHYNESS Vol. 1 Unboxing',
                'video_file' => 'videos/unboxing.mp4',
                'caption' => 'Unboxing SHYNESS Vol. 1 Jacket - first look dan review',
            ],
            [
                'title' => 'Behind The Scene Photoshoot',
                'video_file' => 'videos/bts.mp4',
                'caption' => 'Proses photoshoot koleksi terbaru SHYNESS',
            ],
        ];

        foreach ($videos as $video) {
            Video::firstOrCreate(
                ['title' => $video['title']],
                $video
            );
        }
    }
}
