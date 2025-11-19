<?php

namespace App\Livewire\Production;

use App\Models\Product;
use App\Models\InventoryLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Run extends Component
{
    public $product_id;
    public $quantity_produced;
    public $date;

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required',
            'quantity_produced' => 'required|numeric|min:1'
        ]);

        DB::transaction(function () {
            $product = Product::with('recipes.material')->find($this->product_id);

            // 1. Cek Stok Bahan Baku Cukup Gak?
            foreach ($product->recipes as $recipe) {
                $totalNeeded = $recipe->quantity_needed * $this->quantity_produced;
                if ($recipe->material->current_stock < $totalNeeded) {
                    // Lempar error jika bahan kurang
                    throw new \Exception("Stok " . $recipe->material->name . " kurang! Butuh: " . $totalNeeded . ", Ada: " . $recipe->material->current_stock);
                }
            }

            // 2. Tambah Stok Barang Jadi (Kecap)
            $product->increment('current_stock', $this->quantity_produced);
            
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'production_in', // Hasil Produksi Masuk
                'quantity' => $this->quantity_produced,
                'date' => $this->date,
                'notes' => 'Hasil Produksi Harian'
            ]);

            // 3. Kurangi Stok Bahan Baku (Kedelai) Sesuai Resep
            foreach ($product->recipes as $recipe) {
                $totalUsed = $recipe->quantity_needed * $this->quantity_produced;
                
                $recipe->material->decrement('current_stock', $totalUsed);
                
                InventoryLog::create([
                    'product_id' => $recipe->material_id,
                    'user_id' => Auth::id(),
                    'type' => 'production_in', // Material Keluar untuk Produksi (Kita catat negatif manual atau logikanya adjustment)
                    // Note: type production_in biasanya positif, kita bisa pakai adjustment atau bikin type baru 'material_used'
                    // Agar simple, kita simpan quantity negatif
                    'quantity' => -($totalUsed),
                    'date' => $this->date,
                    'notes' => 'Dipakai produksi ' . $product->name
                ]);
            }
        });

        session()->flash('message', 'Produksi Berhasil! Stok otomatis terupdate.');
        $this->reset(['product_id', 'quantity_produced']);
    }

    public function render()
    {
        // Hanya tampilkan Barang Jadi (Goods) yang punya resep
        return view('livewire.production.run', [
            'products' => Product::where('type', 'goods')->get()
        ])->layout('layouts.app');
    }
}