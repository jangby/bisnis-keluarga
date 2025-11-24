<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filterType = 'goods'; 
    public $search = ''; // [BARU] Variabel untuk search

    public function render()
    {
        $products = Product::where('type', $this->filterType)
            ->with('product_line')
            // [BARU] Logika Pencarian
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            // Urutkan: Stok sedikit diatas, lalu nama
            ->orderBy('current_stock', 'asc') 
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('livewire.product.index', [
            'products' => $products
        ])->layout('layouts.app');
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    // Reset halaman ke 1 saat mengetik search
    public function updatedSearch()
    {
        $this->resetPage();
    }
}