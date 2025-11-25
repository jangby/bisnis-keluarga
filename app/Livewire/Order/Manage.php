<?php

namespace App\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public $filterStatus = 'all'; // all, pending, processing, completed, cancelled
    
    // Variabel Modal Proses
    public $selectedOrder = null;
    public $targetWalletId;
    public $showProcessModal = false;

    public function render()
    {
        $query = Order::with(['items', 'user'])->latest();

        if ($this->filterStatus != 'all') {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.order.manage', [
            'orders' => $query->paginate(10),
            'wallets' => Wallet::all() // Untuk pilihan di modal
        ])->layout('layouts.app');
    }

    // --- LOGIKA UTAMA ---

    public function confirmProcess($orderId)
    {
        $this->selectedOrder = Order::find($orderId);
        $this->targetWalletId = Wallet::first()->id ?? null; // Default pilih wallet pertama
        $this->showProcessModal = true;
    }

    public function processOrder(WhatsAppService $wa) // <--- INJECT SERVICE DISINI
    {
        $this->validate([
            'targetWalletId' => 'required|exists:wallets,id',
        ]);

        $order = $this->selectedOrder;
        
        if ($order->status != 'pending') {
            $this->showProcessModal = false;
            return;
        }

        $defaultProductLineId = \App\Models\ProductLine::first()->id ?? 1;

        DB::transaction(function () use ($order, $defaultProductLineId, $wa) {
            // 1. UPDATE STATUS & STOK (Sama seperti sebelumnya)
            $order->update(['status' => 'processing']); 

            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                $product->decrement('current_stock', $item->quantity);
                InventoryLog::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'sale_out',
                    'quantity' => -($item->quantity),
                    'date' => now(),
                    'notes' => "Order Web #{$order->id}"
                ]);
            }

            // 2. KEUANGAN (Sama seperti sebelumnya)
            $wallet = Wallet::find($this->targetWalletId);
            $wallet->increment('balance', $order->total_amount);
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'product_line_id' => $defaultProductLineId,
                'type' => 'income',
                'amount' => $order->total_amount,
                'description' => "Penjualan Web #{$order->id}",
                'transaction_date' => now(),
                'category' => 'Penjualan'
            ]);

            // 3. [BARU] KIRIM NOTIFIKASI WA (DIPROSES)
            if ($order->guest_phone) {
                $msg  = "Halo kak {$order->guest_name}! 👋\n\n";
                $msg .= "Pesanan kamu *#{$order->id}* sudah kami terima dan sedang *DIPROSES/DIMASAK*.\n";
                $msg .= "Mohon ditunggu ya! Kami akan kabari lagi kalau sudah siap dikirim/diambil.\n\n";
                $msg .= "Terima kasih sudah memesan di Dapur Keluarga! 🍲";
                
                $wa->sendText($order->guest_phone, $msg);
            }
        });

        $this->showProcessModal = false;
        $this->selectedOrder = null;
        session()->flash('message', 'Pesanan diproses & Notifikasi WA terkirim!');
    }

    public function rejectOrder($orderId, WhatsAppService $wa) // <--- INJECT SERVICE JUGA
    {
        $order = Order::find($orderId);
        
        if ($order->status == 'pending') {
            $order->update(['status' => 'cancelled']);
            
            // [BARU] KIRIM NOTIFIKASI WA (DITOLAK)
            if ($order->guest_phone) {
                $msg  = "Mohon maaf kak {$order->guest_name} 🙏\n\n";
                $msg .= "Pesanan kamu *#{$order->id}* terpaksa kami *BATALKAN* saat ini (mungkin karena stok habis atau toko tutup).\n";
                $msg .= "Silakan hubungi admin jika ada pertanyaan.\n\n";
                $msg .= "Sampai jumpa di pesanan berikutnya!";
                
                $wa->sendText($order->guest_phone, $msg);
            }

            session()->flash('message', 'Pesanan ditolak & Notifikasi WA terkirim.');
        } 
    }

    public function markAsCompleted($orderId, WhatsAppService $wa)
    {
        $order = Order::find($orderId);
        $order->update(['status' => 'completed']);

        // [BARU] KIRIM NOTIFIKASI WA (SELESAI)
        if ($order->guest_phone) {
            $msg  = "Pesanan Selesai! ✅\n\n";
            $msg .= "Pesanan *#{$order->id}* sudah selesai. Selamat menikmati hidangannya kak {$order->guest_name}!\n";
            $msg .= "Ditunggu pesanan selanjutnya ya. ⭐";
            
            $wa->sendText($order->guest_phone, $msg);
        }

        session()->flash('message', 'Pesanan selesai & Notifikasi WA terkirim!');
    }
}