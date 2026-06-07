<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Diskon Welcome 10%',
                'description' => 'Diskon 10% untuk pembelian pertama',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'minimum_order_amount' => 200000,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'SHYNESS50K',
                'name' => 'Diskon Flat Rp 50.000',
                'description' => 'Diskon Rp 50.000 untuk pembelian minimal Rp 300.000',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'minimum_order_amount' => 300000,
                'usage_limit' => 50,
                'usage_limit_per_user' => 2,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'VIP20',
                'name' => 'Diskon VIP 20%',
                'description' => 'Diskon 20% untuk member VIP',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'minimum_order_amount' => 500000,
                'usage_limit' => null,
                'usage_limit_per_user' => 3,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
