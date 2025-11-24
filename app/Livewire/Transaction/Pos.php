<?php

namespace App\Livewire\Transaction;

use App\Models\Product;
use App\Models\ProductLine;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use App\Models\Contact;
use App\Models\Debt;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pos extends Component
{
    public $cart = []; 
    public $totalAmount = 0;
    public $wallet_id; 
    
    // Variabel Kasbon
    public $contact_id;
    public $is_debt = false;
    public $notes = '';

    // Filter & Search
    public $search = '';
    public $category_id = 'all';
    
    // [BARU] Opsi Cetak Struk
    public $print_receipt = true; 

    public function mount()
    {
        $this->wallet_id = Wallet::where('name', 'like', '%Kas%')->first()->id ?? Wallet::first()->id ?? null;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        // Validasi stok (Opsional: jika ingin membolehkan stok minus, hapus blok ini)
        if ($product->current_stock <= 0) {
            $this->dispatch('show-toast', type: 'error', message: 'Stok Habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] + 1 > $product->current_stock) {
                $this->dispatch('show-toast', type: 'error', message: 'Stok tidak cukup!');
                return;
            }
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'qty' => 1,
                'line_id' => $product->product_line_id, 
            ];
        }

        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']--;
            if ($this->cart[$productId]['qty'] <= 0) {
                unset($this->cart[$productId]);
            }
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalAmount = 0;
        foreach ($this->cart as $item) {
            $this->totalAmount += ($item['price'] * $item['qty']);
        }
    }

    public function selectCategory($id)
    {
        $this->category_id = $id;
    }

    // Fungsi Checkout Utama
    public function checkout()
    {
        if (empty($this->cart)) return;

        if ($this->is_debt && empty($this->contact_id)) {
            $this->dispatch('show-toast', type: 'error', message: 'Pilih Pelanggan untuk Kasbon!');
            return;
        }

        // 1. Simpan Data Struk SEMENTARA sebelum cart dihapus
        $receiptData = null;
        if ($this->print_receipt) {
            $customerName = 'Umum';
            if ($this->is_debt && $this->contact_id) {
                $customerName = Contact::find($this->contact_id)->name;
            }
            
            $receiptData = [
                'store_name' => config('app.name', 'BisnisKu'),
                'date' => Carbon::now()->format('d/m/Y H:i'),
                'cashier' => Auth::user()->name,
                'items' => $this->cart, // Kirim array cart
                'total' => $this->totalAmount,
                'payment_type' => $this->is_debt ? 'KASBON' : 'TUNAI',
                'customer' => $customerName,
                'notes' => $this->notes ?? '-',
            ];
        }

        // 2. Proses Database
        DB::transaction(function () {
            $salesPerLine = [];
            $cogsPerLine = [];

            foreach ($this->cart as $item) {
                $product = Product::find($item['id']);
                
                // Inventory Log
                InventoryLog::create([
                    'product_id' => $item['id'],
                    'user_id' => Auth::id(),
                    'type' => 'sale_out',
                    'quantity' => -($item['qty']), 
                    'date' => Carbon::now(),
                    'notes' => $this->is_debt ? 'Penjualan Kasbon' : 'Penjualan Kasir'
                ]);

                // Kurangi Stok
                $product->decrement('current_stock', $item['qty']);

                // Hitung Keuangan
                if (!isset($salesPerLine[$item['line_id']])) {
                    $salesPerLine[$item['line_id']] = 0;
                    $cogsPerLine[$item['line_id']] = 0;
                }
                $salesPerLine[$item['line_id']] += ($item['price'] * $item['qty']);
                $cogsPerLine[$item['line_id']] += ($product->base_price * $item['qty']);
            }

            if ($this->is_debt) {
                // UTANG
                Debt::create([
                    'user_id' => Auth::id(),
                    'contact_id' => $this->contact_id,
                    'type' => 'receivable',
                    'amount' => $this->totalAmount,
                    'remaining' => $this->totalAmount,
                    'status' => 'unpaid',
                    'due_date' => Carbon::now()->addDays(7),
                    'notes' => 'Kasbon: ' . $this->notes
                ]);
                // Expense HPP
                foreach ($cogsPerLine as $lineId => $cogs) {
                     if ($cogs > 0) {
                        FinanceRecord::create([
                            'user_id' => Auth::id(),
                            'product_line_id' => $lineId,
                            'wallet_id' => $this->wallet_id,
                            'type' => 'expense',
                            'amount' => $cogs,
                            'category' => 'HPP Penjualan (Kasbon)',
                            'transaction_date' => Carbon::now(),
                            'notes' => 'Beban HPP Kasbon',
                        ]);
                    }
                }
            } else {
                // TUNAI
                foreach ($salesPerLine as $lineId => $total) {
                    FinanceRecord::create([
                        'user_id' => Auth::id(),
                        'product_line_id' => $lineId,
                        'wallet_id' => $this->wallet_id,
                        'type' => 'income',
                        'amount' => $total,
                        'category' => 'Penjualan',
                        'transaction_date' => Carbon::now(),
                        'notes' => 'Penjualan Tunai',
                    ]);

                    if ($cogsPerLine[$lineId] > 0) {
                        FinanceRecord::create([
                            'user_id' => Auth::id(),
                            'product_line_id' => $lineId,
                            'wallet_id' => $this->wallet_id,
                            'type' => 'expense',
                            'amount' => $cogsPerLine[$lineId],
                            'category' => 'HPP Penjualan',
                            'transaction_date' => Carbon::now(),
                            'notes' => 'HPP Tunai',
                        ]);
                    }
                }
                if($this->wallet_id) {
                    Wallet::find($this->wallet_id)->increment('balance', $this->totalAmount);
                }
            }
        });

        // 3. Trigger Print (Jika dicentang) SEBELUM reset cart
        if ($this->print_receipt && $receiptData) {
            $this->dispatch('trigger-print-receipt', data: $receiptData);
        }

        // 4. Reset
        $this->cart = [];
        $this->totalAmount = 0;
        $this->is_debt = false;
        $this->notes = '';
        $this->contact_id = null;
        
        $this->dispatch('close-modal'); // Tutup modal konfirmasi
        session()->flash('message', 'Transaksi Berhasil!');
    }

    public function render()
    {
        $products = Product::query()
            ->where('type', 'goods')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id != 'all', function($q) {
                $q->where('product_line_id', $this->category_id);
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.transaction.pos', [
            'products' => $products,
            'categories' => ProductLine::all(), 
            'wallets' => Wallet::all(),
            'customers' => Contact::where('type', 'customer')->get() 
        ])->layout('layouts.app');
    }
}