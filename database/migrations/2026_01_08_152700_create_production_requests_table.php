<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_requests', function (Blueprint $table) {
            $table->id();
            // Siapa yang request
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Detail barang yang diminta (Disimpan dalam format JSON/Teks)
            $table->text('items'); 
            
            // Catatan tambahan
            $table->text('notes')->nullable();
            
            // Status: pending (diajukan), approved (disetujui), rejected (ditolak)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_requests');
    }
};