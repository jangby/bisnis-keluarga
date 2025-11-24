<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FinanceRecord;            // <--- Tambah ini
use App\Observers\FinanceRecordObserver; // <--- Tambah ini
use App\Observers\ProductObserver;
use App\Models\Product;
use App\Models\User;
use App\Models\Contact;
use App\Observers\GeneralObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pasang CCTV (Observer) ke Model Penting
        Product::observe(GeneralObserver::class);
        //FinanceRecord::observe(GeneralObserver::class);
        User::observe(GeneralObserver::class);
        Contact::observe(GeneralObserver::class);
        
        // Set Locale Carbon (WIB)
        \Carbon\Carbon::setLocale('id');
        config(['app.locale' => 'id']);
    }
}