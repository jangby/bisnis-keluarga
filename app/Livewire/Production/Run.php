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

    // Variabel bahan (Editable)
    public $materialsUsed = []; 

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function updatedProductId() { $this->calculateMaterials(); }
    public function updatedQuantityProduced() { $this->calculateMaterials(); }

    public function calculateMaterials()
    {
        $this->materialsUsed = [];

        if ($this->product_id && $this->quantity_produced > 0) {
            $product = Product::with('recipes.material')->find($this->product_id);
            
            foreach ($product->recipes as $recipe) {
                $standardQty = $recipe->quantity_needed * $this->quantity_produced;

                $this->materialsUsed[] = [
                    'material_id' => $recipe->material_id,
                    'name' => $recipe->material->name,
                    'unit' => $recipe->material->unit,
                    'current_stock' => $recipe->material->current_stock,
                    'standard_qty' => $standardQty, 
                    'actual_qty' => $standardQty,   
                    'cost_per_unit' => $recipe->material->base_price 
                ];
            }
        }
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required',
            'quantity_produced' => 'required|numeric|min:1',
            'materialsUsed.*.actual_qty' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $product = Product::find($this->product_id);
            $totalProductionCost = 0;

            // 1. Proses Bahan Baku
            foreach ($this->materialsUsed as $item) {
                // Validasi Stok
                if ($item['current_stock'] < $item['actual_qty']) {
                    throw new \Exception("Stok " . $item['name'] . " kurang! Butuh: " . $item['actual_qty']);
                }

                $cost = $item['actual_qty'] * $item['cost_per_unit'];
                $totalProductionCost += $cost;

                // Update Stok Bahan (Quietly agar tidak spam log)
                $material = Product::find($item['material_id']);
                $material->current_stock -= $item['actual_qty'];
                $material->saveQuietly(); // <--- PENTING: Silent update

                // Catat Log Gudang (Tetap ada untuk audit stok)
                InventoryLog::create([
                    'product_id' => $item['material_id'],
                    'user_id' => Auth::id(),
                    'type' => 'production_in', // Tipe: Masuk ke Produksi (Keluar dari Gudang)
                    'quantity' => -($item['actual_qty']), 
                    'date' => $this->date,
                    'notes' => 'Dipakai Produksi: ' . $product->name
                ]);
            }

            // 2. Update Barang Jadi (HPP & Stok)
            $oldStockValue = $product->current_stock * $product->base_price;
            $totalNewStock = $product->current_stock + $this->quantity_produced;
            
            if ($totalNewStock > 0) {
                $newBasePrice = ($oldStockValue + $totalProductionCost) / $totalNewStock;
                $product->base_price = $newBasePrice;
            }

            $product->current_stock += $this->quantity_produced;
            $product->saveQuietly(); // <--- PENTING: Silent update juga disini

            // 3. Catat Log Gudang untuk Barang Jadi
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'production_in', // Hasil Produksi
                'quantity' => $this->quantity_produced,
                'date' => $this->date,
                'notes' => 'Hasil Produksi (HPP Baru: Rp ' . number_format($product->base_price, 0) . ')'
            ]);

            // 4. [BARU] Catat SATU Log Aktivitas yang Jelas
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Produksi',
                'subject_type' => 'Production',
                'subject_id' => null,
                'description' => "Memproduksi {$this->quantity_produced} {$product->unit} {$product->name}",
                'properties' => ['color' => 'bg-orange-100 text-orange-700', 'icon' => '🏭'],
                'ip_address' => request()->ip()
            ]);
        });

        session()->flash('message', 'Produksi Berhasil Disimpan!');
        $this->reset(['product_id', 'quantity_produced', 'materialsUsed']);
    }

    public function render()
    {
        return view('livewire.production.run', [
            'products' => Product::where('type', 'goods')->get()
        ])->layout('layouts.app');
    }
}