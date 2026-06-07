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
                'code' => 'bank_transfer',
                'description' => 'Pembayaran melalui transfer bank (BI-Fast, RTGS, atau ATM)',
                'tax_percentage' => 0,
                'is_active' => false,
            ],
            [
                'name' => 'Kartu Kredit',
                'code' => 'credit_card',
                'description' => 'Pembayaran menggunakan kartu kredit Visa, Mastercard, atau JCB',
                'tax_percentage' => 2.5,
                'is_active' => false,
            ],
            [
                'name' => 'E-Wallet',
                'code' => 'ewallet',
                'description' => 'Pembayaran menggunakan e-wallet seperti GoPay, OVO, Dana, atau ShopeePay',
                'tax_percentage' => 1.0,
                'is_active' => false,
            ],
            [
                'name' => 'QRIS',
                'code' => 'QRIS',
                'description' => 'Pembayaran menggunakan QRIS - Scan QR dengan aplikasi bank atau e-wallet',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'BRI Virtual Account',
                'code' => 'BRIVA',
                'description' => 'Pembayaran via Virtual Account BRI',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'BCA Virtual Account',
                'code' => 'BCAVA',
                'description' => 'Pembayaran via Virtual Account BCA',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'BNI Virtual Account',
                'code' => 'BNIVA',
                'description' => 'Pembayaran via Virtual Account BNI',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Mandiri Virtual Account',
                'code' => 'MANDIRIVA',
                'description' => 'Pembayaran via Virtual Account Mandiri',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'BSI Virtual Account',
                'code' => 'BSIVA',
                'description' => 'Pembayaran via Virtual Account BSI',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Tunai (Cash)',
                'code' => 'cash',
                'description' => 'Pembayaran tunai di kasir dengan verifikasi kamera',
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'COD (Cash on Delivery)',
                'code' => 'cod',
                'description' => 'Pembayaran tunai saat barang diterima',
                'tax_percentage' => 0,
                'is_active' => false,
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
