<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\FinanceRecord;
use App\Models\Attendance;
use App\Models\Product;
use Carbon\Carbon;

class Home extends Component
{
    public function render()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // 1. Data Keuangan (Hari Ini & Bulan Ini)
        $incomeToday = FinanceRecord::where('type', 'income')->whereDate('transaction_date', $today)->sum('amount');
        $expenseToday = FinanceRecord::where('type', 'expense')->whereDate('transaction_date', $today)->sum('amount');
        
        $incomeMonth = FinanceRecord::where('type', 'income')->whereMonth('transaction_date', $thisMonth)->whereYear('transaction_date', $thisYear)->sum('amount');
        $expenseMonth = FinanceRecord::where('type', 'expense')->whereMonth('transaction_date', $thisMonth)->whereYear('transaction_date', $thisYear)->sum('amount');
        
        $profitMonth = $incomeMonth - $expenseMonth;

        // 2. Data Kehadiran Hari Ini
        $totalStaff = \App\Models\User::whereIn('role', ['staff', 'marketing', 'production', 'finance'])->count();
        $presentToday = Attendance::whereDate('date', $today)->where('status', 'hadir')->distinct('user_id')->count();
        $attendancePercent = $totalStaff > 0 ? ($presentToday / $totalStaff) * 100 : 0;

        // 3. Stok Menipis (Alert)
        // Asumsi stok menipis jika di bawah 10
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        return view('livewire.owner.home', [
            'incomeToday' => $incomeToday,
            'profitMonth' => $profitMonth,
            'presentToday' => $presentToday,
            'totalStaff' => $totalStaff,
            'attendancePercent' => $attendancePercent,
            'lowStockCount' => $lowStockCount,
        ]);
    }
}