<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->date('start_date');
            $table->date('end_date');
            
            $table->enum('type', ['sakit', 'izin', 'cuti']);
            $table->text('reason');
            
            // Path foto bukti (nanti disimpan format .webp)
            $table->string('proof_path')->nullable(); 
            
            // Status persetujuan oleh Owner
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Alasan jika ditolak
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};