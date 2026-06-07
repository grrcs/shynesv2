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
        Schema::create('seller_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('base_price', 12, 2); // harga dari penjual
            $table->decimal('markup_percentage', 5, 2)->default(0); // markup admin
            $table->decimal('final_price', 12, 2); // harga jual = base_price + markup
            $table->integer('stock')->default(0);
            $table->integer('weight')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'inactive'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); // linked product after approval
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_products');
    }
};
