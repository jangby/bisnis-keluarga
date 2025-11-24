<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filterType = 'goods'; 

    public function render()
    {
        $products = Product::where('type', $this->filterType)
            ->with('product_line')
            // LOGIKA BARU: Stok paling sedikit muncul duluan
            ->orderBy('current_stock', 'asc') 
            ->paginate(12);

        return view('livewire.product.index', [
            'products' => $products
        ])->layout('layouts.app');
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }
}