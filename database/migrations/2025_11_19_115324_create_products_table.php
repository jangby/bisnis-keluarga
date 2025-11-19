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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        // Link ke Divisi (PENTING agar tidak tertukar Kecap/Sistik)
        $table->foreignId('product_line_id')->constrained('product_lines')->onDelete('cascade');
        
        $table->string('code')->unique(); // SKU
        $table->string('name');
        $table->decimal('base_price', 15, 2)->default(0); // HPP
        $table->decimal('sell_price', 15, 2)->default(0); // Harga Jual Umum
        $table->integer('current_stock')->default(0);
        $table->string('unit'); // Pcs, Kg, Bal
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
