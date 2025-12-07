<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date')->index(); // Index untuk pencarian cepat per tanggal
            
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            
            // Simpan lokasi saat absen (untuk bukti jika diperlukan)
            $table->string('lat_in')->nullable();
            $table->string('long_in')->nullable();
            $table->string('lat_out')->nullable();
            $table->string('long_out')->nullable();
            
            // Status kehadiran
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha', 'libur'])->default('hadir');
            $table->text('note')->nullable(); // Catatan opsional user
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};