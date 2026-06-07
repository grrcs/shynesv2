<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('invoice_number');
            $table->string('payment_channel')->nullable()->after('payment_reference');
            $table->string('payment_token')->nullable()->after('payment_channel');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_reference', 'payment_channel', 'payment_token']);
        });
    }
};
