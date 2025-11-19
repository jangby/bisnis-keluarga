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
    // 1. Update Tabel Produk: Tambah Tipe
    Schema::table('products', function (Blueprint $table) {
        // 'goods' = Barang Jadi (Kecap), 'material' = Bahan Baku (Tepung)
        $table->enum('type', ['goods', 'material'])->default('goods')->after('name');
    });

    // 2. Buat Tabel Resep (Bahan apa saja untuk bikin 1 produk)
    Schema::create('product_recipes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produk Jadi (Kecap)
        $table->foreignId('material_id')->constrained('products'); // Bahan (Kedelai)
        $table->decimal('quantity_needed', 10, 3); // Butuh berapa? (misal 0.5 kg)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
