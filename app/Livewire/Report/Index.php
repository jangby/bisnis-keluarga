<?php

namespace App\Livewire\Report;

use App\Models\ProductLine;
use App\Models\FinanceRecord;
use Livewire\Component;
use Carbon\Carbon;

class Index extends Component
{
    // Filter Waktu
    public $month;
    public $year;

    public function mount()
    {
        // Default bulan & tahun ini
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function render()
    {
        // 1. Ambil Semua Divisi
        $lines = ProductLine::all();
        $reportData = [];
        $grandTotalProfit = 0;

        foreach ($lines as $line) {
            // Hitung Pemasukan per Divisi bulan ini
            $income = FinanceRecord::where('product_line_id', $line->id)
                ->whereYear('transaction_date', $this->year)
                ->whereMonth('transaction_date', $this->month)
                ->where('type', 'income')
                ->sum('amount');

            // Hitung Pengeluaran per Divisi bulan ini
            $expense = FinanceRecord::where('product_line_id', $line->id)
                ->whereYear('transaction_date', $this->year)
                ->whereMonth('transaction_date', $this->month)
                ->where('type', 'expense')
                ->sum('amount');

            // Laba Bersih Divisi
            $profit = $income - $expense;
            
            $grandTotalProfit += $profit;

            // Masukkan ke array data
            $reportData[] = [
                'name' => $line->name,
                'desc' => $line->description,
                'income' => $income,
                'expense' => $expense,
                'profit' => $profit
            ];
        }

        return view('livewire.report.index', [
            'reportData' => $reportData,
            'grandTotalProfit' => $grandTotalProfit,
            'months' => [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ]
        ])->layout('layouts.app');
    }
}