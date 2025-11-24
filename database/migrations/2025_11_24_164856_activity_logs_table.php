<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Siapa pelakunya
            $table->string('action'); // Create, Update, Delete, Login, etc
            $table->string('subject_type'); // Model apa? (Product, FinanceRecord, dll)
            $table->unsignedBigInteger('subject_id')->nullable(); // ID datanya
            $table->text('description'); // Keterangan manusiawi
            $table->json('properties')->nullable(); // Data detail perubahan (Old vs New)
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};