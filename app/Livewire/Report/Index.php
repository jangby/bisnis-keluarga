<?php

namespace App\Livewire\Report;

use App\Models\FinanceRecord;
use App\Models\ProductLine;
use App\Models\Wallet;
use Livewire\Component;
use Carbon\Carbon;

class Index extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        // Default bulan ini
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function render()
    {
        // 1. Ambil Semua Divisi (Kecap, Sistik, Umum)
        $lines = ProductLine::all();
        
        $reportPerLine = [];
        $totalOmzet = 0;
        $totalExpense = 0;

        foreach ($lines as $line) {
            // Filter per Divisi & Periode Bulan Ini
            $query = FinanceRecord::where('product_line_id', $line->id)
                        ->whereMonth('transaction_date', $this->month)
                        ->whereYear('transaction_date', $this->year);

            $income = (clone $query)->where('type', 'income')->sum('amount');
            $expense = (clone $query)->where('type', 'expense')->sum('amount'); // Termasuk HPP

            $reportPerLine[] = [
                'id' => $line->id,
                'name' => $line->name,
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense, // Laba Bersih per Divisi
                'description' => $line->description
            ];

            $totalOmzet += $income;
            $totalExpense += $expense;
        }

        // 2. Ambil Total Uang Real (Saldo Dompet)
        $realBalance = Wallet::sum('balance');

        // 3. History Transaksi Terakhir
        $transactions = FinanceRecord::with('product_line')
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->latest('transaction_date')
            ->get();

        return view('livewire.report.index', [
            'reportPerLine' => $reportPerLine,
            'totalOmzet' => $totalOmzet,
            'totalExpense' => $totalExpense,
            'netProfit' => $totalOmzet - $totalExpense,
            'realBalance' => $realBalance,
            'transactions' => $transactions
        ])->layout('layouts.app');
    }
}