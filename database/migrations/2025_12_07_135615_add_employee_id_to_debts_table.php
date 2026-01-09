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
    Schema::table('debts', function (Blueprint $table) {
        // Link ke tabel users (karyawan), nullable karena bisa jadi utang orang luar (bukan karyawan)
        $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('debts', function (Blueprint $table) {
        $table->dropForeign(['employee_id']);
        $table->dropColumn('employee_id');
    });
}
};
