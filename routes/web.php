<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

// Import Controllers & Livewire
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\FinanceRecord;

// Livewire Components
use App\Livewire\Product\Index as ProductIndex;
use App\Livewire\Product\Form as ProductForm;
use App\Livewire\Transaction\Pos;
use App\Livewire\Order\Manage as OrderManage;
use App\Livewire\Order\History as OrderHistory;
use App\Livewire\Finance\Create as FinanceCreate;
use App\Livewire\Finance\Approval as FinanceApproval;
use App\Livewire\Finance\DebtManager;
use App\Livewire\Finance\History as FinanceHistory;
use App\Livewire\Report\Index as ReportIndex;
use App\Livewire\Report\Detail as ReportDetail;
use App\Livewire\Production\RequestMaterial;
use App\Livewire\Production\Run as ProductionRun;
use App\Livewire\Production\History as ProductionHistory;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Purchase\Create as PurchaseCreate;
use App\Livewire\Activity\Index as ActivityLogIndex;
use App\Livewire\Payroll\Index as PayrollIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $products = Product::query()
        ->where('current_stock', '>', 0)
        ->where('sell_price', '>', 0)
        ->when(Schema::hasColumn('products', 'is_featured'), function ($query) {
             $query->where('is_featured', true);
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

// --- DASHBOARD (Logic Data Dashboard) ---
Route::get('dashboard', function () {
    $user = Auth::user();
    $today = \Carbon\Carbon::now()->format('Y-m-d');

    // 1. DATA OWNER & FINANCE
    $totalSaldo = 0;
    $recentTransactions = [];
    if (in_array($user->role, ['owner', 'finance'])) {
        $totalSaldo = Wallet::sum('balance');
        $recentTransactions = FinanceRecord::with('product_line')->latest()->take(5)->get();
    }

    // 2. DATA PRODUKSI & INVENTORY
    $lowStockItems = [];
    $totalProducts = 0;
    $totalMaterials = 0;
    // Update: Inventory masuk sini
    if (in_array($user->role, ['owner', 'production', 'inventory'])) { 
        $lowStockItems = Product::where('current_stock', '<=', 5)->get();
        $totalProducts = Product::where('type', 'goods')->count();
        $totalMaterials = Product::where('type', 'material')->count();
    }

    // 3. DATA MARKETING / PENJUALAN
    $todayRevenue = 0;
    $todayOrdersCount = 0;
    $recentOrders = [];
    if (in_array($user->role, ['owner', 'marketing'])) {
        $todayRevenue = \App\Models\Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total_amount'); // Pastikan pakai total_amount sesuai DB

        $todayOrdersCount = \App\Models\Order::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        $recentOrders = \App\Models\Order::with('user')
            ->whereDate('created_at', $today)
            ->latest()->take(5)->get();
    }

    return view('dashboard', [
        'totalSaldo' => $totalSaldo,
        'transactions' => $recentTransactions,
        'lowStockItems' => $lowStockItems,
        'totalProducts' => $totalProducts,
        'totalMaterials' => $totalMaterials,
        'todayRevenue' => $todayRevenue,
        'todayOrdersCount' => $todayOrdersCount,
        'recentOrders' => $recentOrders,
    ]);
})->middleware(['auth', 'verified', 'staff'])->name('dashboard');


// ====================================================
// GROUP UTAMA: AUTHENTICATED STAFF
// ====================================================
Route::middleware(['auth', 'staff'])->group(function () {
    
    // --- 1. AREA UMUM (Semua Staff) ---
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Absensi (Semua Staff)
    Route::get('/attendance', function () { return view('attendance.index'); })->name('attendance.index');
    Route::get('/attendance/monitoring', App\Livewire\Attendance\Monitoring::class)->name('attendance.monitoring');


    // --- 2. MANAJEMEN PRODUK & STOK (Owner, Produksi, Gudang) ---
    // Note: Marketing & Finance tidak akses ini
    Route::middleware(['role:owner,production,inventory'])->group(function () {
        Route::get('/products', ProductIndex::class)->name('products.index');
        Route::get('/products/create', ProductForm::class)->name('products.create');
        Route::get('/products/{id}/edit', ProductForm::class)->name('products.edit');
        
        // REQUEST BAHAN (Gudang & Produksi bisa akses)
        Route::get('/production/request', RequestMaterial::class)->name('production.request');
    });


    // --- 3. KHUSUS PRODUKSI (Hanya Owner & Produksi) ---
    // Gudang (Inventory) TIDAK BOLEH akses ini
    Route::middleware(['role:owner,production'])->group(function () {
        Route::get('/production/run', ProductionRun::class)->name('production.run');
        Route::get('/production/history', ProductionHistory::class)->name('production.history');
    });


    // --- 4. KEUANGAN & PURCHASING (Owner & Finance) ---
    Route::middleware(['role:owner,finance'])->group(function () {
        Route::get('/finance/create', FinanceCreate::class)->name('finance.create');
        Route::get('/finance/approval', FinanceApproval::class)->name('finance.approval');
        Route::get('/finance/debts', DebtManager::class)->name('finance.debts');
        Route::get('/finance/history', FinanceHistory::class)->name('finance.history');
        Route::get('/report', ReportIndex::class)->name('report.index');
        Route::get('/report/{id}', ReportDetail::class)->name('report.detail');
        Route::get('/purchase', PurchaseCreate::class)->name('purchase.create');
        Route::get('/payroll', PayrollIndex::class)->name('payroll.index'); // Aman di sini
    });


    // --- 5. PENJUALAN & KASIR (Owner, Marketing, Finance) ---
    // Finance kadang butuh akses POS/Order juga untuk cek nota
    Route::middleware(['role:owner,marketing,finance'])->group(function () {
        Route::get('/pos', Pos::class)->name('pos.index');
        Route::get('/orders', OrderManage::class)->name('orders.manage');
        Route::get('/orders/history', OrderHistory::class)->name('orders.history');
    });


    // --- 6. LOG AKTIVITAS (Owner & Inventory) ---
    // Sesuai request: Inventory butuh akses log ini
    Route::middleware(['role:owner,inventory'])->group(function () {
        Route::get('/activity-log', ActivityLogIndex::class)->name('activity.index');
    });


    // --- 7. SUPER ADMIN / SETTINGS (Hanya Owner) ---
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/settings', SettingsIndex::class)->name('settings.index');
        Route::get('/web-management', \App\Livewire\Web\Manage::class)->name('web.manage');
        Route::get('/attendance/settings', App\Livewire\Attendance\Settings::class)->name('attendance.settings');
    });

});


// --- ROUTE PUBLIC / TOKO ONLINE ---
Route::get('/menu', \App\Livewire\Front\Catalog::class)->name('front.index');
Route::get('/cart', \App\Livewire\Front\Cart::class)->name('front.cart');
Route::get('/account', \App\Livewire\Front\Account::class)->name('front.account');


require __DIR__.'/auth.php';