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
        Schema::create('shipping_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('courier_name')->nullable(); // JNE, J&T, SiCepat, etc
            $table->string('service_type')->nullable(); // REG, OKE, YES
            $table->string('tracking_number')->nullable();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->text('sender_address')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->string('receiver_city')->nullable();
            $table->string('receiver_province')->nullable();
            $table->string('receiver_postal_code')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_details');
    }
};
