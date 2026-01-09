<?php

namespace App\Livewire\Production;

use App\Models\InventoryLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    // Variabel Filter
    public $startDate;
    public $endDate;
    public $filterType = 'goods'; // Default: Barang Jadi (production_in)
    public $filterUser = '';

    public function mount()
    {
        // Default tanggal: 7 hari terakhir agar tidak terlalu berat loadnya
        $this->startDate = now()->subDays(7)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    // Reset halaman ke 1 setiap kali filter berubah
    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }
    public function updatedFilterType() { $this->resetPage(); }
    public function updatedFilterUser() { $this->resetPage(); }

    public function render()
    {
        // Query Dasar: Ambil Log yang berhubungan dengan Produksi (Masuk & Keluar)
        $query = InventoryLog::with(['user', 'product'])
            ->whereIn('type', ['production_in', 'production_out']);

        // 1. Filter Jenis (Barang Jadi vs Bahan Baku)
        if ($this->filterType == 'goods') {
            $query->where('type', 'production_in');
        } elseif ($this->filterType == 'material') {
            $query->where('type', 'production_out');
        }

        // 2. Filter User
        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        // 3. Filter Tanggal
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::whereIn('role', ['owner', 'production'])->get(); // Ambil list user produksi

        return view('livewire.production.history', [
            'logs' => $logs,
            'users' => $users
        ])->layout('layouts.app');
    }
}