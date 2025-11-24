<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Pesanan (Head)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Bisa null jika Guest
            $table->string('guest_name')->nullable(); // Nama pemesan (jika guest)
            $table->string('guest_phone')->nullable(); // WA pemesan
            $table->text('delivery_address')->nullable(); // Alamat kirim
            
            $table->decimal('total_amount', 15, 2);
            // Status: pending (baru masuk), processing (dimasak), shipping (dikirim), completed (selesai), cancelled
            $table->enum('status', ['pending', 'processing', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // 2. Tabel Detail Item Pesanan
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(); // Jangan cascade delete agar history aman
            $table->string('product_name'); // Simpan nama saat beli (jaga-jaga produk dihapus/ubah nama)
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Harga saat transaksi terjadi
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};