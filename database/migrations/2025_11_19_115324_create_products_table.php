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
        $table->foreignId('product_line_id')->constrained('product_lines')->onDelete('cascade');
        
        $table->string('code')->unique();
        $table->string('name');
        $table->decimal('base_price', 15, 2)->default(0);
        $table->decimal('sell_price', 15, 2)->default(0);
        
        $table->integer('current_stock')->default(0);
        
        // --- TAMBAHAN BARU DISINI ---
        $table->integer('min_stock')->default(5); // Batas minimum untuk notifikasi
        // ----------------------------

        $table->string('unit');
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
