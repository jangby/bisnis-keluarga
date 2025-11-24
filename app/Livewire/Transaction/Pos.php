<?php

namespace App\Livewire\Transaction;

use App\Models\Product;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use App\Models\Contact; // [BARU] Import Model Contact
use App\Models\Debt;    // [BARU] Import Model Debt
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pos extends Component
{
    public $cart = []; 
    public $totalAmount = 0;
    public $wallet_id; 
    
    // [BARU] Variabel untuk Kasbon
    public $contact_id;      // Siapa pelanggannya?
    public $is_debt = false; // Apakah ngutang?
    public $notes = '';      // Catatan tambahan

    public function mount()
    {
        $this->wallet_id = Wallet::where('name', 'like', '%Kas%')->first()->id ?? Wallet::first()->id;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if ($product->current_stock <= 0) {
            session()->flash('error', 'Stok habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] + 1 > $product->current_stock) {
                session()->flash('error', 'Stok tidak cukup!');
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
                'max_stock' => $product->current_stock
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

    public function checkout()
    {
        if (empty($this->cart)) {
            return;
        }

        // [BARU] Validasi jika pilih Kasbon, Wajib pilih Pelanggan
        if ($this->is_debt && empty($this->contact_id)) {
            session()->flash('error', 'Jika Kasbon, wajib pilih nama Pelanggan!');
            return;
        }

        DB::transaction(function () {
            $salesPerLine = [];
            $cogsPerLine = [];

            foreach ($this->cart as $item) {
                $product = Product::find($item['id']);

                // 1. Potong Stok (Inventory Log)
                InventoryLog::create([
                    'product_id' => $item['id'],
                    'user_id' => Auth::id(),
                    'type' => 'sale_out',
                    'quantity' => -($item['qty']), 
                    'date' => Carbon::now(),
                    'notes' => $this->is_debt ? 'Penjualan Kasbon' : 'Penjualan Kasir'
                ]);

                // 2. Update Stok Master Product
                $product->decrement('current_stock', $item['qty']);

                // 3. Hitung total per divisi
                if (!isset($salesPerLine[$item['line_id']])) {
                    $salesPerLine[$item['line_id']] = 0;
                    $cogsPerLine[$item['line_id']] = 0;
                }
                $salesPerLine[$item['line_id']] += ($item['price'] * $item['qty']);
                $cogsPerLine[$item['line_id']] += ($product->base_price * $item['qty']);
            }

            // [LOGIKA BARU PEMBAYARAN]
            if ($this->is_debt) {
                // --- JIKA KASBON (UTANG) ---
                
                // 1. Catat ke Tabel Debts (Piutang)
                Debt::create([
                    'user_id' => Auth::id(),
                    'contact_id' => $this->contact_id,
                    'type' => 'receivable',
                    'amount' => $this->totalAmount,
                    'remaining' => $this->totalAmount, // [TAMBAHAN] Sisa utang awal = Total utang
                    'status' => 'unpaid',
                    'due_date' => Carbon::now()->addDays(7),
                    'notes' => 'Kasbon Kasir: ' . $this->notes
                ]);

                // PENTING:
                // Saat Kasbon, kita TIDAK menambah saldo Wallet (Uang belum masuk).
                // Kita juga TIDAK mencatat FinanceRecord type 'income' agar Laporan Kas Real tidak kacau.
                // Namun, kita tetap mencatat HPP sebagai beban karena barang sudah hilang.
                
                foreach ($cogsPerLine as $lineId => $cogs) {
                     if ($cogs > 0) {
                        FinanceRecord::create([
                            'user_id' => Auth::id(),
                            'product_line_id' => $lineId,
                            'wallet_id' => $this->wallet_id, // Mengurangi "Nilai Aset" walau kas tidak berubah
                            'type' => 'expense',
                            'amount' => $cogs,
                            'category' => 'HPP Penjualan (Kasbon)',
                            'transaction_date' => Carbon::now(),
                            'notes' => 'Beban HPP untuk transaksi kasbon',
                        ]);
                    }
                }

            } else {
                // --- JIKA TUNAI (CASH) ---
                
                // 1. Catat Pemasukan & HPP seperti biasa
                foreach ($salesPerLine as $lineId => $total) {
                    // Income
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

                    // HPP
                    if ($cogsPerLine[$lineId] > 0) {
                        FinanceRecord::create([
                            'user_id' => Auth::id(),
                            'product_line_id' => $lineId,
                            'wallet_id' => $this->wallet_id,
                            'type' => 'expense',
                            'amount' => $cogsPerLine[$lineId],
                            'category' => 'HPP Penjualan',
                            'transaction_date' => Carbon::now(),
                            'notes' => 'Beban Pokok Penjualan',
                        ]);
                    }
                }

                // 2. Tambah Uang di Dompet
                Wallet::find($this->wallet_id)->increment('balance', $this->totalAmount);
            }
        });

        // Reset Cart & Form
        $this->cart = [];
        $this->totalAmount = 0;
        $this->is_debt = false;
        $this->notes = '';
        $this->contact_id = null;
        
        session()->flash('message', 'Transaksi Berhasil Disimpan!');
    }

    public function render()
    {
        return view('livewire.transaction.pos', [
            'products' => Product::where('current_stock', '>', 0)->where('type', 'goods')->get(),
            'wallets' => Wallet::all(),
            // [BARU] Ambil daftar pelanggan untuk dropdown
            'customers' => Contact::where('type', 'customer')->get() 
        ])->layout('layouts.app');
    }
}