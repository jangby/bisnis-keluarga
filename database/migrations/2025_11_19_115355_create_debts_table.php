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
            // Relasi
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang input
            $table->foreignId('contact_id')->constrained()->onDelete('cascade'); // Siapa yang utang
            
            // Jenis Utang
            $table->enum('type', ['payable', 'receivable']); // Payable = Kita Utang, Receivable = Orang Utang ke Kita
            
            // Nilai Uang
            $table->decimal('amount', 15, 2); // Total Utang Awal
            
            // [PERBAIKAN] Kolom Sisa Utang (Wajib ada untuk cicilan)
            // Kita set nullable dulu atau default 0, nanti diisi via controller
            $table->decimal('remaining', 15, 2)->default(0); 
            
            // Status & Tanggal
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('due_date')->nullable(); // Jatuh Tempo
            $table->text('notes')->nullable();
            
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