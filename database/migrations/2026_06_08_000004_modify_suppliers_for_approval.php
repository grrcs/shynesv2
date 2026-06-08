<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL doesn't support MODIFY COLUMN, use raw SQL
        DB::statement('ALTER TABLE suppliers ALTER COLUMN tenant_id DROP NOT NULL');

        Schema::table('suppliers', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('approved_at');
            $table->dropColumn('rejected_at');
        });
    }
};
