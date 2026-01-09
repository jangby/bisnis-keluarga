<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita ubah kolom 'type' agar menerima nilai 'production_out'
        // Daftar ini HARUS mencakup semua nilai lama + nilai baru
        DB::statement("ALTER TABLE inventory_logs MODIFY COLUMN type ENUM('production_in', 'sale_out', 'damaged', 'adjustment', 'purchase_in', 'production_out') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke daftar lama (Hati-hati, ini bisa error jika sudah ada data 'production_out')
        DB::statement("ALTER TABLE inventory_logs MODIFY COLUMN type ENUM('production_in', 'sale_out', 'damaged', 'adjustment', 'purchase_in') NOT NULL");
    }
};