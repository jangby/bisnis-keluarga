<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Kategori Web (Etalase)
        Schema::create('web_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Kategori: "Promo", "Sambal", dll
            $table->string('slug')->unique(); // Untuk URL nanti (promo, sambal)
            $table->string('icon')->nullable(); // Ikon/Emoji
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Pivot (Penghubung Banyak Produk ke Banyak Kategori)
        Schema::create('web_category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // Mencegah duplikasi produk yang sama di kategori yang sama
            $table->unique(['web_category_id', 'product_id']); 
        });

        // 3. Update Tabel Produk (Fitur Diskon)
        Schema::table('products', function (Blueprint $table) {
            // Harga Coret (Jika diisi, maka harga asli dicoret)
            $table->decimal('discount_price', 15, 2)->nullable()->after('sell_price'); 
            // Opsional: Label Diskon (misal: "10%")
            $table->integer('discount_percentage')->nullable()->after('discount_price'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_category_product');
        Schema::dropIfExists('web_categories');
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount_price', 'discount_percentage']);
        });
    }
};