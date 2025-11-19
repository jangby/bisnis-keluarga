<?php

namespace App\Livewire\Transaction;

use App\Models\Product;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pos extends Component
{
    public $cart = []; // Keranjang belanja
    public $totalAmount = 0;
    public $wallet_id; // Uang masuk ke mana?

    public function mount()
    {
        // Default uang masuk ke Kas Tunai
        $this->wallet_id = Wallet::where('name', 'like', '%Kas%')->first()->id ?? Wallet::first()->id;
    }

    // 1. Tambah Barang ke Keranjang
    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if ($product->current_stock <= 0) {
            session()->flash('error', 'Stok habis!');
            return;
        }

        // Cek jika barang sudah ada di cart, tambah qty
        if (isset($this->cart[$productId])) {
            // Cek stok lagi
            if ($this->cart[$productId]['qty'] + 1 > $product->current_stock) {
                session()->flash('error', 'Stok tidak cukup!');
                return;
            }
            $this->cart[$productId]['qty']++;
        } else {
            // Jika belum ada, masukkan baru
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'qty' => 1,
                'line_id' => $product->product_line_id, // Penting untuk pisah laba rugi
                'max_stock' => $product->current_stock
            ];
        }

        $this->calculateTotal();
    }

    // 2. Kurangi/Hapus Barang
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

    // 3. PROSES PEMBAYARAN (CHECKOUT)
    public function checkout()
    {
        if (empty($this->cart)) {
            return;
        }

        DB::transaction(function () {
            // A. Kelompokkan total per Divisi (Kecap/Sistik)
            $salesPerLine = [];

            foreach ($this->cart as $item) {
                // 1. Potong Stok (Inventory Log)
                InventoryLog::create([
                    'product_id' => $item['id'],
                    'user_id' => Auth::id(),
                    'type' => 'sale_out',
                    'quantity' => -($item['qty']), // Negatif artinya keluar
                    'date' => Carbon::now(),
                    'notes' => 'Penjualan Kasir'
                ]);

                // 2. Update Stok Master Product
                Product::where('id', $item['id'])->decrement('current_stock', $item['qty']);

                // 3. Hitung total per divisi untuk Finance Record
                if (!isset($salesPerLine[$item['line_id']])) {
                    $salesPerLine[$item['line_id']] = 0;
                }
                $salesPerLine[$item['line_id']] += ($item['price'] * $item['qty']);
            }

            // B. Buat Catatan Keuangan (Finance Record)
            // Sistem pintar: Jika jual Kecap & Sistik, bikin 2 laporan keuangan terpisah otomatis
            foreach ($salesPerLine as $lineId => $total) {
                FinanceRecord::create([
                    'user_id' => Auth::id(),
                    'product_line_id' => $lineId,
                    'wallet_id' => $this->wallet_id,
                    'type' => 'income',
                    'amount' => $total,
                    'category' => 'Penjualan',
                    'transaction_date' => Carbon::now(),
                    'notes' => 'Penjualan Kasir (Otomatis)',
                ]);
            }

            // C. Update Saldo Dompet
            Wallet::find($this->wallet_id)->increment('balance', $this->totalAmount);
        });

        // Reset Cart
        $this->cart = [];
        $this->totalAmount = 0;
        session()->flash('message', 'Transaksi Berhasil!');
    }

    public function render()
    {
        return view('livewire.transaction.pos', [
            'products' => Product::where('current_stock', '>', 0)->get(), // Hanya tampilkan yg ada stok
            'wallets' => Wallet::all()
        ])->layout('layouts.app');
    }
}