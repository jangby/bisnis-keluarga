<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Contact;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Create extends Component
{
    // Input Form
    public $product_id;
    public $quantity;
    public $total_cost;
    public $unit_price;
    
    public $wallet_id;
    public $supplier_id;
    public $date;
    public $notes;

    // State Modal Konfirmasi
    public $showConfirmationModal = false;

    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->wallet_id = Wallet::first()->id ?? null;
    }

    public function updatedTotalCost() { $this->calculateUnitPrice(); }
    public function updatedQuantity() { $this->calculateUnitPrice(); }

    public function calculateUnitPrice()
    {
        if ($this->quantity > 0 && $this->total_cost > 0) {
            $this->unit_price = $this->total_cost / $this->quantity;
        } else {
            $this->unit_price = 0;
        }
    }

    public function confirmSave()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1',
            'total_cost' => 'required|numeric|min:0',
            'wallet_id' => 'required|exists:wallets,id',
            'date' => 'required|date',
        ]);

        $this->showConfirmationModal = true;
    }

    public function save()
    {
        DB::transaction(function () {
            $product = Product::find($this->product_id);
            
            // 1. Catat Uang Keluar
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'product_line_id' => $product->product_line_id, 
                'contact_id' => $this->supplier_id,
                'type' => 'expense',
                'amount' => $this->total_cost,
                'category' => 'Belanja Bahan Baku',
                'transaction_date' => $this->date,
                'notes' => "Beli {$product->name} ({$this->quantity} {$product->unit})",
                'is_paid' => true
            ]);

            // 2. Kurangi Saldo Dompet
            Wallet::find($this->wallet_id)->decrement('balance', $this->total_cost);

            // 3. Hitung HPP Baru (Average) & Stok
            $oldValue = $product->current_stock * $product->base_price;
            $newValue = $this->total_cost; 
            $totalStock = $product->current_stock + $this->quantity;

            if ($totalStock > 0) {
                $newBasePrice = ($oldValue + $newValue) / $totalStock;
                $product->base_price = $newBasePrice;
            }
            
            // Tambah stok
            $product->current_stock += $this->quantity;
            
            // [PENTING] Pakai saveQuietly() agar TIDAK MEMICU Log "Mengubah Data Produk"
            $product->saveQuietly();

            // 4. Catat Log Gudang
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'purchase_in',
                'quantity' => $this->quantity,
                'date' => $this->date,
                'notes' => 'Pembelian Langsung'
            ]);

            // 5. [BARU] Catat Log Aktivitas Manual (Satu Baris Rapi)
            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Belanja',
                'subject_type' => 'Purchase',
                'subject_id' => null,
                'description' => "Membeli {$product->name} sebanyak {$this->quantity} {$product->unit} (Total: Rp " . number_format($this->total_cost, 0, ',', '.') . ")",
                'properties' => ['color' => 'bg-indigo-100 text-indigo-700', 'icon' => '🛍️'],
                'ip_address' => request()->ip()
            ]);
        });

        session()->flash('message', 'Pembelian Berhasil! Stok & Keuangan telah diupdate.');
        
        $this->showConfirmationModal = false;
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.purchase.create', [
            'products' => Product::where('type', 'material')->orderBy('name')->get(),
            'wallets' => Wallet::all(),
            'suppliers' => Contact::where('type', 'supplier')->orderBy('name')->get()
        ])->layout('layouts.app');
    }
}