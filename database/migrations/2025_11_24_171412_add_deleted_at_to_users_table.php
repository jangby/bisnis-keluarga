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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom deleted_at untuk Soft Deletes
            // 'after' opsional, agar kolomnya rapi di database (setelah updated_at)
            $table->softDeletes()->after('updated_at'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom deleted_at jika migrasi dibatalkan (rollback)
            $table->dropSoftDeletes();
        });
    }
};