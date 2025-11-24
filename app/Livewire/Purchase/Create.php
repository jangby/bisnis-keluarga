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

    // [BARU] State Modal Konfirmasi
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

    // [BARU] Validasi Awal (Sebelum Buka Modal)
    public function confirmSave()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1',
            'total_cost' => 'required|numeric|min:0',
            'wallet_id' => 'required|exists:wallets,id',
            'date' => 'required|date',
        ]);

        // Jika valid, tampilkan modal
        $this->showConfirmationModal = true;
    }

    // Eksekusi Simpan (Setelah dikonfirmasi di Modal)
    public function save()
    {
        DB::transaction(function () {
            $product = Product::find($this->product_id);
            
            // 1. Catat Uang Keluar (Expense)
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'product_line_id' => $product->product_line_id, 
                'contact_id' => $this->supplier_id,
                'type' => 'expense',
                'amount' => $this->total_cost,
                'category' => 'Belanja Bahan Baku',
                'transaction_date' => $this->date,
                'notes' => "Beli {$product->name} ({$this->quantity} {$product->unit}) " . ($this->notes ? "- {$this->notes}" : ''),
                'is_paid' => true
            ]);

            // 2. Kurangi Saldo Dompet
            Wallet::find($this->wallet_id)->decrement('balance', $this->total_cost);

            // 3. Update HPP (Weighted Average)
            $oldValue = $product->current_stock * $product->base_price;
            $newValue = $this->total_cost; 
            $totalStock = $product->current_stock + $this->quantity;

            if ($totalStock > 0) {
                $newBasePrice = ($oldValue + $newValue) / $totalStock;
                $product->base_price = $newBasePrice;
            }

            // 4. Update Stok & Simpan
            $product->increment('current_stock', $this->quantity);
            $product->save();

            // 5. Catat Log Gudang
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'purchase_in',
                'quantity' => $this->quantity,
                'date' => $this->date,
                'notes' => 'Pembelian Langsung'
            ]);
        });

        session()->flash('message', 'Pembelian Berhasil! Stok & Keuangan telah diupdate.');
        
        // Tutup Modal & Redirect
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