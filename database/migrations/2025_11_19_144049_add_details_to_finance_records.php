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
    Schema::table('finance_records', function (Blueprint $table) {
        // Tambahan untuk fitur Teman Bisnis
        $table->foreignId('contact_id')->nullable()->constrained(); // Untuk mencatat Supplier/Pelanggan
        $table->foreignId('product_id')->nullable()->constrained(); // Jika transaksi terkait barang spesifik
        
        $table->integer('quantity')->nullable(); // Jumlah barang
        $table->string('unit')->nullable(); // Pcs, Kg, Lusin
        
        $table->string('payment_method')->default('cash'); // cash, transfer, debt (hutang)
        $table->date('due_date')->nullable(); // Jatuh tempo jika hutang
        $table->boolean('is_paid')->default(true); // Status lunas/belum
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_records', function (Blueprint $table) {
            //
        });
    }
};
