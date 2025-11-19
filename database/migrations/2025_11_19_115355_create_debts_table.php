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
    Schema::create('debts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('contact_id')->constrained()->onDelete('cascade');
        
        // Hutang ini milik divisi mana?
        $table->foreignId('product_line_id')->nullable()->constrained('product_lines');
        
        $table->enum('type', ['payable', 'receivable']); // Hutang Kita vs Piutang
        $table->decimal('amount', 15, 2); // Total awal
        $table->decimal('remaining', 15, 2); // Sisa
        $table->date('due_date')->nullable();
        $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
