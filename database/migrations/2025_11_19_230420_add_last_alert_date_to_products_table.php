<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_last_alert_date_to_products_table.php
public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        // Kolom untuk mencatat kapan terakhir dikirim notif WA
        $table->timestamp('last_alert_date')->nullable()->after('min_stock');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('last_alert_date');
    });
}
};
