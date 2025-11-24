<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FinanceRecord;
use App\Models\ProductLine;
use Carbon\Carbon;

class History extends Component
{
    use WithPagination;

    // Filter Properties
    public $dateStart;
    public $dateEnd;
    public $type = 'all'; // income, expense, all
    public $lineId = 'all'; // Divisi tertentu atau semua
    public $search = '';

    public function mount()
    {
        // Default: Tanggal awal bulan ini s/d hari ini
        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->format('Y-m-d');
    }

    // Reset halaman jika filter berubah
    public function updated($property)
    {
        if (in_array($property, ['search', 'type', 'lineId', 'dateStart', 'dateEnd'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = FinanceRecord::with('product_line', 'contact')
            // Filter Tanggal
            ->whereBetween('transaction_date', [$this->dateStart, $this->dateEnd])
            
            // Filter Tipe (Masuk/Keluar)
            ->when($this->type != 'all', function ($q) {
                $q->where('type', $this->type);
            })
            
            // Filter Divisi
            ->when($this->lineId != 'all', function ($q) {
                $q->where('product_line_id', $this->lineId);
            })

            // Filter Pencarian (Catatan / Kategori / Nominal)
            ->when($this->search, function ($q) {
                $q->where(function($sub) {
                    $sub->where('category', 'like', '%' . $this->search . '%')
                        ->orWhere('notes', 'like', '%' . $this->search . '%')
                        ->orWhere('amount', 'like', '%' . $this->search . '%');
                });
            })
            
            // Urutan Terbaru
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Ringkasan untuk Header (Berdasarkan Filter yang aktif)
        $summaryIncome = (clone $query)->where('type', 'income')->sum('amount');
        $summaryExpense = (clone $query)->where('type', 'expense')->sum('amount');

        return view('livewire.finance.history', [
            'transactions' => $query->paginate(20),
            'productLines' => ProductLine::all(),
            'summaryIncome' => $summaryIncome,
            'summaryExpense' => $summaryExpense
        ])->layout('layouts.app');
    }
}