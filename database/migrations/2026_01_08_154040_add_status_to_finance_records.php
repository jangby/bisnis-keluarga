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
            // Kita set default 'approved' agar data lama dianggap sudah sah/disetujui
            // Jadi saldo keuangan Anda tidak berantakan
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_records', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};