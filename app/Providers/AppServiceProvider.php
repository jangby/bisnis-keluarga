<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FinanceRecord;            // <--- Tambah ini
use App\Observers\FinanceRecordObserver; // <--- Tambah ini
use App\Observers\ProductObserver;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Aktifkan Observer
        FinanceRecord::observe(FinanceRecordObserver::class);
        Product::observe(ProductObserver::class);
    }
}