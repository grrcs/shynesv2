<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Deactivate old payment options that don't work with WijayaPay
        DB::table('payment_options')
            ->whereIn('code', ['bank_transfer', 'credit_card', 'ewallet', 'virtual_account', 'VA'])
            ->update(['is_active' => false]);

        // Update Cash.id QRIS to WijayaPay QRIS
        DB::table('payment_options')
            ->where('code', 'QRIS')
            ->update([
                'name' => 'QRIS',
                'description' => 'Pembayaran menggunakan QRIS - Scan QR dengan aplikasi bank atau e-wallet',
            ]);

        // Insert new WijayaPay VA options
        $vaOptions = [
            ['name' => 'BRI Virtual Account', 'code' => 'BRIVA', 'description' => 'Pembayaran via Virtual Account BRI', 'tax_percentage' => 0, 'is_active' => true],
            ['name' => 'BCA Virtual Account', 'code' => 'BCAVA', 'description' => 'Pembayaran via Virtual Account BCA', 'tax_percentage' => 0, 'is_active' => true],
            ['name' => 'BNI Virtual Account', 'code' => 'BNIVA', 'description' => 'Pembayaran via Virtual Account BNI', 'tax_percentage' => 0, 'is_active' => true],
            ['name' => 'Mandiri Virtual Account', 'code' => 'MANDIRIVA', 'description' => 'Pembayaran via Virtual Account Mandiri', 'tax_percentage' => 0, 'is_active' => true],
            ['name' => 'BSI Virtual Account', 'code' => 'BSIVA', 'description' => 'Pembayaran via Virtual Account BSI', 'tax_percentage' => 0, 'is_active' => true],
        ];

        foreach ($vaOptions as $option) {
            DB::table('payment_options')->updateOrInsert(
                ['code' => $option['code']],
                $option
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-activate old options
        DB::table('payment_options')
            ->whereIn('code', ['bank_transfer', 'credit_card', 'ewallet', 'virtual_account', 'VA'])
            ->update(['is_active' => true]);

        // Revert QRIS name
        DB::table('payment_options')
            ->where('code', 'QRIS')
            ->update([
                'name' => 'Cash.id QRIS',
                'description' => 'Pembayaran menggunakan QRIS via Cash.id - Scan QR dengan aplikasi bank atau e-wallet',
            ]);

        // Remove new VA options
        DB::table('payment_options')
            ->whereIn('code', ['BRIVA', 'BCAVA', 'BNIVA', 'MANDIRIVA', 'BSIVA'])
            ->delete();
    }
};
