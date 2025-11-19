<?php

namespace App\Livewire\Production;

use App\Models\Product;
use App\Models\PurchaseRequest;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RequestMaterial extends Component
{
    public $product_id;
    public $quantity;
    public $notes;

    public function save()
    {
        $this->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);

        PurchaseRequest::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'notes' => $this->notes,
            'status' => 'pending'
        ]);

        session()->flash('message', 'Permintaan terkirim ke Keuangan!');
        $this->reset();
    }

    public function render()
{
    return view('livewire.production.request-material', [
        // FILTER HANYA MATERIAL (Bahan Baku)
        'products' => Product::where('type', 'material')->orderBy('name')->get(), 
        'myRequests' => PurchaseRequest::where('user_id', Auth::id())->latest()->get()
    ])->layout('layouts.app');
}
}