<?php

namespace App\Livewire\Order;

use App\Models\OrderItem;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    // Filter
    public $startDate;
    public $endDate;
    public $filterUser = '';
    public $search = '';

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d'); // Default hari ini biar cepat
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }
    public function updatedFilterUser() { $this->resetPage(); }

    public function render()
    {
        // Query: Ambil Item Penjualan yang Ordernya 'Completed' (Lunas)
        $query = OrderItem::query()
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed'); // Hanya yang lunas
                
                // Filter Tanggal (Berdasarkan tanggal Order)
                if ($this->startDate) {
                    $q->whereDate('created_at', '>=', $this->startDate);
                }
                if ($this->endDate) {
                    $q->whereDate('created_at', '<=', $this->endDate);
                }
                
                // Filter User (Siapa yang jual)
                if ($this->filterUser) {
                    $q->where('user_id', $this->filterUser);
                }
            });

        // Filter Nama Barang
        if ($this->search) {
            $query->where('product_name', 'like', '%' . $this->search . '%');
        }

        // Ambil data, urutkan dari order terbaru
        $items = $query->with('order.user') // Eager load relasi biar ringan
            ->latest() // Urutkan created_at desc (default Laravel)
            ->paginate(20);

        // List User untuk Filter (Hanya Marketing & Owner)
        $users = User::whereIn('role', ['owner', 'marketing'])->get();

        return view('livewire.order.history', [
            'items' => $items,
            'users' => $users
        ])->layout('layouts.app');
    }
}