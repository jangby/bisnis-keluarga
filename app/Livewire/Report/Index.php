<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\ProductLine;
use App\Models\FinanceRecord;
use App\Models\Wallet; // Import Wallet
use Carbon\Carbon;

class Index extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function render()
    {
        // 1. Ambil Data Wallet untuk Info Saldo
        $wallets = Wallet::all();

        // 2. Ambil Ringkasan Per Divisi
        $lines = ProductLine::all();
        $reportData = [];

        foreach ($lines as $line) {
            // Hitung Income & Expense per divisi bulan ini
            $income = FinanceRecord::where('product_line_id', $line->id)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $this->month)
                ->whereYear('transaction_date', $this->year)
                ->sum('amount');

            $expense = FinanceRecord::where('product_line_id', $line->id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $this->month)
                ->whereYear('transaction_date', $this->year)
                ->sum('amount');

            $reportData[] = [
                'id' => $line->id,
                'name' => $line->name,
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense
            ];
        }

        return view('livewire.report.index', [
            'wallets' => $wallets,
            'reportData' => $reportData
        ])->layout('layouts.app');
    }
}