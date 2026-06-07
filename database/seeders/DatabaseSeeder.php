<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'tenant_id' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'pembeli@gmail.com'],
            [
                'name' => 'Pembeli User',
                'email' => 'pembeli@gmail.com',
                'role' => 'pembeli',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ProductMediaSeeder::class,
            ProductVariantSeeder::class,
            BannerSeeder::class,
            PostSeeder::class,
            VideoSeeder::class,
            ConfessionSeeder::class,
            ReviewSeeder::class,
            CouponSeeder::class,
            PaymentOptionSeeder::class,
            AddressSeeder::class,
            LoyaltyPointSeeder::class,
        ]);
    }
}
