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
    Schema::create('purchase_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Siapa yang minta (Produksi)
        $table->foreignId('product_id')->constrained(); // Barang apa
        $table->integer('quantity'); // Berapa banyak
        $table->decimal('estimated_price', 15, 2)->nullable(); // Estimasi harga (opsional)
        
        // status: pending, approved, rejected
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('notes')->nullable(); // Alasan butuh
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
