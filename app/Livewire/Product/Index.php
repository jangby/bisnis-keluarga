<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::with('product_line')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        // PERHATIKAN BARIS DI BAWAH INI:
        // Kita sambungkan view ini dengan layout milik Breeze ('layouts.app')
        return view('livewire.product.index', [
            'products' => $products
        ])->layout('layouts.app'); 
    }
}