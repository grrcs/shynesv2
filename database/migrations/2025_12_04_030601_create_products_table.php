<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relasi ke kategori (Misal: T-Shirt, Jacket)
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('image');
            $table->string('title');
            $table->string('slug'); // URL ramah SEO (misal: shyness-oversized-black)
            $table->text('description');

            // Kolom khusus produk
            $table->bigInteger('price'); // Harga
            $table->integer('stock')->default(0); // Stok
            $table->integer('weight')->default(100); // Berat (gram) - opsional untuk ongkir

            // Link ke E-commerce luar (Shopee/Tokped) jika belum ada sistem cart sendiri
            $table->string('link_shopee')->nullable();

            // Status produk: active (dijual), inactive (disembunyikan), sold_out (habis)
            $table->enum('status', ['active', 'inactive', 'sold_out'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
