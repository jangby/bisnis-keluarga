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
    Schema::table('web_category_product', function (Blueprint $table) {
        $table->timestamps(); // Menambahkan created_at & updated_at
    });
}

public function down(): void
{
    Schema::table('web_category_product', function (Blueprint $table) {
        $table->dropTimestamps();
    });
}
};
