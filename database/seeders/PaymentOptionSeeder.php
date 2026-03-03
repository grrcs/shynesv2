<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentOption;

class PaymentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentOptions = [
            [
                'name' => 'Transfer Bank',
                'description' => 'Pembayaran melalui transfer bank (BI-Fast, RTGS, atau ATM)',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Kartu Kredit',
                'description' => 'Pembayaran menggunakan kartu kredit Visa, Mastercard, atau JCB',
                'tax_percentage' => 2.5,
                'is_active' => true,
            ],
            [
                'name' => 'E-Wallet',
                'description' => 'Pembayaran menggunakan e-wallet seperti GoPay, OVO, Dana, atau ShopeePay',
                'tax_percentage' => 1.0,
                'is_active' => true,
            ],
            [
                'name' => 'Virtual Account',
                'description' => 'Pembayaran melalui virtual account bank',
                'tax_percentage' => 0.5,
                'is_active' => true,
            ],
            [
                'name' => 'QRIS',
                'description' => 'Pembayaran menggunakan QRIS (Quick Response Code Indonesian Standard)',
                'tax_percentage' => 0.7,
                'is_active' => true,
            ],
            [
                'name' => 'COD (Cash on Delivery)',
                'description' => 'Pembayaran tunai saat barang diterima',
                'tax_percentage' => 0,
                'is_active' => false, // Default nonaktif, bisa diaktifkan jika ada fitur delivery
            ],
        ];

        foreach ($paymentOptions as $option) {
            PaymentOption::firstOrCreate(
                ['name' => $option['name']],
                $option
            );
        }
    }
}
