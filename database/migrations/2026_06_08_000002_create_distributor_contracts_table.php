<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributor_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_code', 10)->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('distributor_company');
            $table->string('distributor_contact');
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->decimal('contract_value', 15, 2);
            $table->string('status')->default('active');
            $table->text('file_path');
            $table->string('encryption_key_hash', 64);
            $table->string('tenant_id', 36);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributor_contracts');
    }
};
