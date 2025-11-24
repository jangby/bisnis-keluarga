<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Livewire\Product\Index;
use App\Livewire\Finance\Create;
use App\Livewire\Transaction\Pos;
use App\Livewire\Report\Index as ReportIndex;
use App\Livewire\Product\Form;
use App\Livewire\Production\RequestMaterial;
use App\Livewire\Finance\Approval;
use App\Livewire\Production\Run;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Report\Detail as ReportDetail;
use App\Livewire\Purchase\Create as PurchaseCreate;
use App\Livewire\Finance\History;
use App\Livewire\Activity\Index as ActivityLogIndex;

// Import Model agar dikenali
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Livewire\Finance\DebtManager;

use Illuminate\Support\Facades\Auth; // Jangan lupa baris ini

Route::view('/', 'welcome');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ROUTE DASHBOARD (Dengan Data Keuangan)
Route::get('dashboard', function () {
    $totalSaldo = Wallet::sum('balance');

    // Urutkan berdasarkan Tanggal Transaksi DESC, lalu Waktu Input DESC
    $recentTransactions = FinanceRecord::with('product_line') 
        ->orderBy('transaction_date', 'desc') 
        ->orderBy('created_at', 'desc') // Pastikan yang baru diinput ada di atas
        ->take(5)
        ->get();

    return view('dashboard', [
        'totalSaldo' => $totalSaldo,
        'transactions' => $recentTransactions
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    
    // Route Produk (Gudang)
    Route::get('/products', Index::class)->name('products.index');

    // Route Keuangan (Form Transaksi)
    Route::get('/finance/create', Create::class)->name('finance.create');
    Route::get('/pos', Pos::class)->name('pos.index');
    Route::get('/report', ReportIndex::class)->name('report.index');
    Route::get('/report/{id}', ReportDetail::class)->name('report.detail');
    // Route Tambah Produk Baru
Route::get('/products/create', Form::class)->name('products.create');
Route::get('/production/request', RequestMaterial::class)->name('production.request');
Route::get('/finance/approval', Approval::class)->name('finance.approval');
Route::get('/production/run', Run::class)->name('production.run');
Route::get('/settings', SettingsIndex::class)->name('settings.index');
Route::get('/finance/debts', DebtManager::class)->name('finance.debts');
Route::get('/purchase', PurchaseCreate::class)->name('purchase.create');

// Route Edit Produk (Membawa parameter {id})
Route::get('/products/{id}/edit', Form::class)->name('products.edit');

    // Route Profile Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/finance/history', History::class)->name('finance.history');
Route::get('/activity-log', ActivityLogIndex::class)->name('activity.index');
});

require __DIR__.'/auth.php';