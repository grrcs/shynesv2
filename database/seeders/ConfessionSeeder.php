<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Confession;

class ConfessionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $confessions = [
            ['sender_name' => 'Anonymous', 'content' => 'SHYNESS gave me confidence to be myself. Love the design!'],
            ['sender_name' => 'Sarah', 'content' => 'First time buying from SHYNESS and Im impressed by the quality!'],
            ['sender_name' => 'Anonymous', 'content' => 'Jacket ini sesuai banget sama deskripsi. Bahannya nyaman dan Jahitannya rapi. Recommended!'],
            ['sender_name' => 'Bayu', 'content' => 'Finally found a brand that understands minimalist style. SHYNESS rocks!'],
            ['sender_name' => 'Anonymous', 'content' => 'Pengalaman beli pertama di SHYNESS sangat memuaskan. Customer service ramah dan pengiriman cepat.'],
        ];

        foreach ($confessions as $confession) {
            Confession::firstOrCreate(
                ['content' => substr($confession['content'], 0, 50)],
                $confession
            );
        }
    }
}
