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
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

// Import Model agar dikenali
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Livewire\Finance\DebtManager;

use Illuminate\Support\Facades\Auth; // Jangan lupa baris ini

Route::get('/', function () {
    // AMBIL PRODUK JADI SAJA
    // Filter: Stok > 0, Harga Jual > 0, dan (Opsional) hanya yang ditandai 'is_featured'
    $products = Product::query()
        ->where('current_stock', '>', 0)
        ->where('sell_price', '>', 0) // Filter ampuh membuang bahan baku (asumsi bahan baku harga jualnya 0/kosong)
        ->when(Schema::hasColumn('products', 'is_featured'), function ($query) {
             $query->where('is_featured', true); // Gunakan ini jika kolom is_featured sudah ada
        })
        ->latest()
        ->get();

    return view('welcome', compact('products'));
});

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
Route::get('/web-management', \App\Livewire\Web\Manage::class)->name('web.manage');
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

// Route Area Pengunjung (Toko Online)
Route::get('/menu', \App\Livewire\Front\Catalog::class)->name('front.index');
// Placeholder dulu biar tidak error saat diklik menu bawah
Route::get('/cart', \App\Livewire\Front\Cart::class)->name('front.cart');
Route::get('/account', \App\Livewire\Front\Account::class)->name('front.account');

require __DIR__.'/auth.php';