<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name'); // Untuk info/artikel produk
            $table->decimal('promo_price', 15, 2)->nullable()->after('sell_price'); // Untuk harga coret
            $table->boolean('is_featured')->default(true)->after('current_stock'); // Opsi tampilkan di depan
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description', 'promo_price', 'is_featured']);
        });
    }
};