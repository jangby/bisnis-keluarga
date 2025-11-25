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
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Observer untuk Produk (Stok, dll)
        Product::observe(GeneralObserver::class);

        // 2. Observer untuk Keuangan (PENTING: Aktifkan Keduanya)
        // a. GeneralObserver: Untuk mencatat Log Aktivitas ke database
        FinanceRecord::observe(GeneralObserver::class); 
        
        // b. FinanceRecordObserver: Untuk kirim WA jika transaksi besar (> 5 Juta)
        FinanceRecord::observe(FinanceRecordObserver::class);

        // 3. Observer Lainnya
        User::observe(GeneralObserver::class);
        Contact::observe(GeneralObserver::class);
        
        // Set Locale Waktu Indonesia
        Carbon::setLocale('id');
        config(['app.locale' => 'id']);
    }
}