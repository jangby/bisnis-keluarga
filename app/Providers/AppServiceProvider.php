<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FinanceRecord;            // <--- Tambah ini
use App\Observers\FinanceRecordObserver; // <--- Tambah ini

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
    }
}