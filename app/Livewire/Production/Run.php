<?php

namespace App\Livewire\Production;

use App\Models\Product;
use App\Models\InventoryLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class Run extends Component
{
    // Variabel Modal
    public $showModal = false; // <--- INI KUNCINYA

    // Variabel Form
    public $product_id;
    public $quantity_produced;
    public $date;
    public $materialsUsed = []; 

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }

    // Tombol "Tambah Produksi" ditekan
    public function openModal()
    {
        $this->reset(['product_id', 'quantity_produced', 'materialsUsed']);
        $this->date = Carbon::now()->format('Y-m-d'); // Reset tanggal ke hari ini
        $this->showModal = true;
    }

    // Tombol "Batal" / "Tutup" ditekan
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updatedProductId() { $this->calculateMaterials(); }
    public function updatedQuantityProduced() { $this->calculateMaterials(); }

    #[Computed]
    public function todayLogs()
    {
        return InventoryLog::with('product')
            ->where('type', 'production_in')
            ->whereDate('date', $this->date) // Filter sesuai tanggal yg dipilih (atau hari ini)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    #[Computed]
    public function todayStats()
    {
        $logs = $this->todayLogs();
        return [
            'total_pcs' => $logs->sum('quantity'),
            'total_batch' => $logs->count(),
            'products_count' => $logs->groupBy('product_id')->count()
        ];
    }

    public function calculateMaterials()
    {
        $this->materialsUsed = [];

        if ($this->product_id && $this->quantity_produced > 0) {
            $product = Product::with('recipes.material')->find($this->product_id);
            
            if ($product && $product->recipes) {
                foreach ($product->recipes as $recipe) {
                    $standardQty = $recipe->quantity_needed * $this->quantity_produced;
                    $this->materialsUsed[] = [
                        'material_id' => $recipe->material_id,
                        'name' => $recipe->material->name ?? 'Unknown',
                        'unit' => $recipe->material->unit ?? 'Satuan',
                        'current_stock' => $recipe->material->current_stock ?? 0,
                        'standard_qty' => $standardQty, 
                        'actual_qty' => $standardQty,   
                        'cost_per_unit' => $recipe->material->base_price ?? 0
                    ];
                }
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
                if ($item['current_stock'] < $item['actual_qty']) {
                    throw new \Exception("Stok " . $item['name'] . " kurang!");
                }
                $cost = $item['actual_qty'] * $item['cost_per_unit'];
                $totalProductionCost += $cost;

                $material = Product::find($item['material_id']);
                $material->current_stock -= $item['actual_qty'];
                $material->saveQuietly();

                InventoryLog::create([
                    'product_id' => $item['material_id'],
                    'user_id' => Auth::id(),
                    'type' => 'production_out',
                    'quantity' => -($item['actual_qty']), 
                    'date' => $this->date,
                    'notes' => 'Bahan utk: ' . $product->name . ' (' . $this->quantity_produced . ')'
                ]);
            }

            // 2. Update Barang Jadi
            $oldStockValue = $product->current_stock * $product->base_price;
            $totalNewStock = $product->current_stock + $this->quantity_produced;
            
            if ($totalNewStock > 0) {
                $newBasePrice = ($oldStockValue + $totalProductionCost) / $totalNewStock;
                $product->base_price = $newBasePrice;
            }

            $product->current_stock += $this->quantity_produced;
            $product->saveQuietly();

            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'production_in', 
                'quantity' => $this->quantity_produced,
                'date' => $this->date,
                'notes' => 'Hasil Produksi'
            ]);

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Produksi',
                'subject_type' => 'Production',
                'description' => "Memproduksi {$this->quantity_produced} {$product->unit} {$product->name}",
                'properties' => ['color' => 'bg-orange-100 text-orange-700', 'icon' => '🏭'],
                'ip_address' => request()->ip()
            ]);
        });

        session()->flash('message', 'Produksi Berhasil Disimpan!');
        
        $this->showModal = false; // TUTUP MODAL SETELAH SAVE
        $this->reset(['product_id', 'quantity_produced', 'materialsUsed']);
    }

    public function render()
    {
        return view('livewire.production.run', [
            'products' => Product::where('type', 'goods')->get()
        ])->layout('layouts.app');
    }
}