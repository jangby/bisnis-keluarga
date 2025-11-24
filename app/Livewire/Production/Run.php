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

    // [BARU] Variabel untuk menampung daftar bahan yang akan dipakai (Editable)
    public $materialsUsed = []; 

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }

    // [BARU] Setiap kali Produk atau Jumlah diubah, hitung ulang saran bahan
    public function updatedProductId() { $this->calculateMaterials(); }
    public function updatedQuantityProduced() { $this->calculateMaterials(); }

    public function calculateMaterials()
    {
        // Reset dulu
        $this->materialsUsed = [];

        if ($this->product_id && $this->quantity_produced > 0) {
            $product = Product::with('recipes.material')->find($this->product_id);
            
            foreach ($product->recipes as $recipe) {
                // Hitung standar resep
                $standardQty = $recipe->quantity_needed * $this->quantity_produced;

                // Masukkan ke array agar bisa tampil di tabel dan DIEDIT
                $this->materialsUsed[] = [
                    'material_id' => $recipe->material_id,
                    'name' => $recipe->material->name,
                    'unit' => $recipe->material->unit,
                    'current_stock' => $recipe->material->current_stock,
                    'standard_qty' => $standardQty, // Sebagai referensi
                    'actual_qty' => $standardQty,   // [PENTING] Ini yang nanti disimpan (bisa diedit user)
                    'cost_per_unit' => $recipe->material->base_price // Harga modal saat ini
                ];
            }
        }
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required',
            'quantity_produced' => 'required|numeric|min:1',
            'materialsUsed.*.actual_qty' => 'required|numeric|min:0', // Validasi input bahan
        ]);

        DB::transaction(function () {
            $product = Product::find($this->product_id);
            $totalProductionCost = 0;

            // 1. Loop dari INPUT USER (bukan dari resep database lagi)
            // Ini menangani kasus WASTE atau PENGHEMATAN bahan
            foreach ($this->materialsUsed as $item) {
                
                // Cek stok cukup gak berdasarkan input aktual?
                if ($item['current_stock'] < $item['actual_qty']) {
                    throw new \Exception("Stok " . $item['name'] . " kurang! Butuh: " . $item['actual_qty']);
                }

                // Hitung biaya real (Qty Aktual x Harga Modal)
                $cost = $item['actual_qty'] * $item['cost_per_unit'];
                $totalProductionCost += $cost;

                // Kurangi Stok Bahan (Sesuai Input Aktual)
                // Kita cari object model material-nya untuk update
                $material = Product::find($item['material_id']);
                $material->decrement('current_stock', $item['actual_qty']);

                InventoryLog::create([
                    'product_id' => $item['material_id'],
                    'user_id' => Auth::id(),
                    'type' => 'production_in', 
                    'quantity' => -($item['actual_qty']), // Negatif keluar
                    'date' => $this->date,
                    'notes' => 'Produksi ' . $product->name . ' (Realisasi)'
                ]);
            }

            // 2. Update HPP Barang Jadi (Weighted Average)
            $oldStockValue = $product->current_stock * $product->base_price;
            $totalNewStock = $product->current_stock + $this->quantity_produced;
            
            if ($totalNewStock > 0) {
                // Total Cost dari bahan aktual + Nilai stok lama
                $newBasePrice = ($oldStockValue + $totalProductionCost) / $totalNewStock;
                $product->base_price = $newBasePrice;
            }

            // 3. Tambah Stok Barang Jadi
            $product->increment('current_stock', $this->quantity_produced);
            $product->save(); // Simpan perubahan harga
            
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'production_in',
                'quantity' => $this->quantity_produced,
                'date' => $this->date,
                'notes' => 'Hasil Produksi (Real Cost: Rp ' . number_format($totalProductionCost, 0) . ')'
            ]);
        });

        session()->flash('message', 'Produksi Berhasil! Stok Aktual terpotong & HPP diperbarui.');
        $this->reset(['product_id', 'quantity_produced', 'materialsUsed']);
    }

    public function render()
    {
        return view('livewire.production.run', [
            'products' => Product::where('type', 'goods')->get()
        ])->layout('layouts.app');
    }
}