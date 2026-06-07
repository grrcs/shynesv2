<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoyaltyPoint;
use App\Models\User;
use App\Models\Order;

class LoyaltyPointSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pembeli = User::where('email', 'pembeli@gmail.com')->first();
        $admin = User::where('email', 'admin@gmail.com')->first();

        if ($pembeli) {
            LoyaltyPoint::firstOrCreate(
                [
                    'user_id' => $pembeli->id,
                    'points' => 100,
                    'type' => 'earned',
                ],
                [
                    'description' => 'Welcome bonus points',
                    'expires_at' => now()->addYear(),
                ]
            );
        }

        if ($admin) {
            LoyaltyPoint::firstOrCreate(
                [
                    'user_id' => $admin->id,
                    'points' => 500,
                    'type' => 'earned',
                ],
                [
                    'description' => 'Welcome bonus points for admin',
                    'expires_at' => now()->addYear(),
                ]
            );
        }
    }
}
