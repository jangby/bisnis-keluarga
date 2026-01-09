<?php

namespace App\Livewire\Transaction;

use App\Models\Product;
use App\Models\ProductLine;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use App\Models\Contact;
use App\Models\Debt;
// --- TAMBAHAN IMPORT MODEL ORDER ---
use App\Models\Order;
use App\Models\OrderItem;
// -----------------------------------
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
    
    public $print_receipt = true; 

    public function mount()
    {
        $this->wallet_id = Wallet::where('name', 'like', '%Kas%')->first()->id ?? Wallet::first()->id ?? null;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

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
        // Feedback kecil saat sukses tambah (Opsional)
        // $this->dispatch('show-toast', type: 'success', message: 'Produk ditambahkan'); 
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

    // --- FUNGSI CHECKOUT YANG SUDAH DIPERBAIKI ---
    public function checkout()
    {
        if (empty($this->cart)) return;

        if ($this->is_debt && empty($this->contact_id)) {
            $this->dispatch('show-toast', type: 'error', message: 'Pilih Pelanggan untuk Kasbon!');
            return;
        }

        // Tentukan Nama Pelanggan
        $customerName = 'Pelanggan Umum';
        if ($this->is_debt && $this->contact_id) {
            $customerName = Contact::find($this->contact_id)->name;
        }

        // 1. Data Struk
        $receiptData = null;
        if ($this->print_receipt) {
            $receiptData = [
                'store_name' => config('app.name', 'BisnisKu'),
                'date' => Carbon::now()->format('d/m/Y H:i'),
                'cashier' => Auth::user()->name,
                'items' => $this->cart, 
                'total' => $this->totalAmount,
                'payment_type' => $this->is_debt ? 'KASBON' : 'TUNAI',
                'customer' => $customerName,
                'notes' => $this->notes ?? '-',
            ];
        }

        // 2. Proses Database
        DB::transaction(function () use ($customerName) {
            
            // --- A. SIMPAN KE TABEL ORDERS (Agar Masuk Log Penjualan) ---
            $order = Order::create([
                'user_id' => Auth::id(), // Kasir yang input
                'guest_name' => $customerName,
                'total_amount' => $this->totalAmount,
                'status' => 'completed', // Langsung completed karena POS
                'created_at' => Carbon::now(),
            ]);

            $salesPerLine = [];
            $cogsPerLine = [];

            foreach ($this->cart as $item) {
                $product = Product::find($item['id']);
                
                // --- B. SIMPAN DETAIL ITEM (Order Items) ---
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'], // Simpan nama saat ini
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Inventory Log (Stok Keluar)
                InventoryLog::create([
                    'product_id' => $item['id'],
                    'user_id' => Auth::id(),
                    'type' => 'sale_out',
                    'quantity' => -($item['qty']), 
                    'date' => Carbon::now(),
                    'notes' => 'Penjualan #' . $order->id // Catat No Order di log stok
                ]);

                // Kurangi Stok
                $product->decrement('current_stock', $item['qty']);

                // Hitung Keuangan (Grouping by Kategori)
                if (!isset($salesPerLine[$item['line_id']])) {
                    $salesPerLine[$item['line_id']] = 0;
                    $cogsPerLine[$item['line_id']] = 0;
                }
                $salesPerLine[$item['line_id']] += ($item['price'] * $item['qty']);
                $cogsPerLine[$item['line_id']] += ($product->base_price * $item['qty']);
            }

            // --- C. URUS KEUANGAN & UTANG ---
            if ($this->is_debt) {
                // Catat Utang
                Debt::create([
                    'user_id' => Auth::id(),
                    'contact_id' => $this->contact_id,
                    'type' => 'receivable',
                    'amount' => $this->totalAmount,
                    'remaining' => $this->totalAmount,
                    'status' => 'unpaid',
                    'due_date' => Carbon::now()->addDays(7),
                    'notes' => 'Kasbon Order #' . $order->id
                ]);
                
                // Catat Expense HPP saja (Pemasukan belum diakui cash, tapi HPP sudah keluar)
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
                            'notes' => 'Beban HPP Order #' . $order->id,
                        ]);
                    }
                }
            } else {
                // TUNAI (Catat Pemasukan & HPP)
                foreach ($salesPerLine as $lineId => $total) {
                    FinanceRecord::create([
                        'user_id' => Auth::id(),
                        'product_line_id' => $lineId,
                        'wallet_id' => $this->wallet_id,
                        'type' => 'income',
                        'amount' => $total,
                        'category' => 'Penjualan',
                        'transaction_date' => Carbon::now(),
                        'notes' => 'Penjualan Order #' . $order->id,
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
                            'notes' => 'HPP Order #' . $order->id,
                        ]);
                    }
                }
                
                // Tambah Saldo Dompet
                if($this->wallet_id) {
                    Wallet::find($this->wallet_id)->increment('balance', $this->totalAmount);
                }
            }
        });

        // Catat Log Aktivitas
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Transaksi',
            'subject_type' => 'POS',
            'subject_id' => null,
            'description' => 'Transaksi POS senilai Rp ' . number_format($this->totalAmount, 0, ',', '.'),
            'properties' => ['color' => 'bg-purple-100 text-purple-700', 'icon' => '🛒'],
            'ip_address' => request()->ip()
        ]);

        // 3. Trigger Print Struk
        if ($this->print_receipt && $receiptData) {
            $this->dispatch('trigger-print-receipt', data: $receiptData);
        }

        // 4. Reset & Notifikasi
        $this->cart = [];
        $this->totalAmount = 0;
        $this->is_debt = false;
        $this->notes = '';
        $this->contact_id = null;
        
        $this->dispatch('close-modal'); // Tutup modal checkout
        
        // PERBAIKAN NOTIFIKASI: Gunakan dispatch agar toast muncul
        $this->dispatch('show-toast', type: 'success', message: 'Transaksi Berhasil Disimpan!'); 
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