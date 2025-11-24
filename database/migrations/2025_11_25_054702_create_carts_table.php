<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Keranjang Utama
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Bisa null jika pengunjung belum login (Guest)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Session ID untuk mengenali Guest
            $table->string('session_id')->nullable()->index(); 
            $table->timestamps();
        });

        // 2. Item dalam Keranjang
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            // Simpan harga saat masuk keranjang (antisipasi perubahan harga)
            $table->decimal('price_at_add', 15, 2); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};