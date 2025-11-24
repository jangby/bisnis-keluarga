<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\ProductLine;
use App\Models\FinanceRecord;
use Livewire\WithPagination;
use Carbon\Carbon;

class Detail extends Component
{
    use WithPagination;

    public $lineId;
    public $lineName;
    public $month;
    public $year;

    public function mount($id)
    {
        $this->lineId = $id;
        $this->lineName = ProductLine::find($id)->name;
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function render()
    {
        // 1. Ambil Transaksi Real Divisi Ini
        $transactions = FinanceRecord::where('product_line_id', $this->lineId)
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        // 2. Hitung Total Real Divisi Ini
        $realIncome = FinanceRecord::where('product_line_id', $this->lineId)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->sum('amount');

        $realExpense = FinanceRecord::where('product_line_id', $this->lineId)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->sum('amount');

        // 3. LOGIKA KHUSUS: Jika ini halaman OPERASIONAL (Biasanya ID 1 atau yang namanya mengandung 'Operasional'/'Umum')
        // Kita asumsikan ID 1 adalah Operasional/Umum, atau cek berdasarkan nama
        $isOperational = stripos($this->lineName, 'Operasional') !== false || stripos($this->lineName, 'Umum') !== false;
        
        $injectedProfits = [];
        $totalInjectedIncome = 0;

        if ($isOperational) {
            // Ambil SEMUA divisi SELAIN Operasional
            $otherLines = ProductLine::where('id', '!=', $this->lineId)->get();

            foreach ($otherLines as $other) {
                // Hitung Profit Divisi Lain
                $inc = FinanceRecord::where('product_line_id', $other->id)
                        ->where('type', 'income')
                        ->whereMonth('transaction_date', $this->month)
                        ->whereYear('transaction_date', $this->year)
                        ->sum('amount');
                
                $exp = FinanceRecord::where('product_line_id', $other->id)
                        ->where('type', 'expense')
                        ->whereMonth('transaction_date', $this->month)
                        ->whereYear('transaction_date', $this->year)
                        ->sum('amount');
                
                $profit = $inc - $exp;

                // Hanya jika profit positif, kita anggap sebagai 'Setoran' ke Operasional
                if ($profit > 0) {
                    $injectedProfits[] = [
                        'name' => 'Setoran Laba ' . $other->name,
                        'amount' => $profit
                    ];
                    $totalInjectedIncome += $profit;
                }
            }
        }

        // Total Akhir untuk Tampilan
        $finalIncome = $realIncome + $totalInjectedIncome;
        $finalProfit = $finalIncome - $realExpense;

        return view('livewire.report.detail', [
            'transactions' => $transactions,
            'realIncome' => $realIncome,
            'realExpense' => $realExpense,
            'isOperational' => $isOperational,
            'injectedProfits' => $injectedProfits,
            'finalIncome' => $finalIncome,
            'finalProfit' => $finalProfit
        ])->layout('layouts.app');
    }
}