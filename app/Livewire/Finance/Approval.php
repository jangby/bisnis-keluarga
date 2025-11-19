<?php

namespace App\Livewire\Finance;

use App\Models\PurchaseRequest;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\InventoryLog;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Approval extends Component
{
    public $selectedRequest; // Request yang sedang dibuka
    public $pay_amount; // Nominal yang harus dibayar Real
    public $wallet_id; // Pakai uang mana

    public function approve($id)
    {
        // Buka Modal Konfirmasi
        $this->selectedRequest = PurchaseRequest::find($id);
        $this->pay_amount = $this->selectedRequest->product->base_price * $this->selectedRequest->quantity; // Estimasi awal
        $this->wallet_id = Wallet::first()->id;
    }

    public function confirmApprove()
    {
        // LOGIKA INTI: APPROVE = BAYAR & TAMBAH STOK
        DB::transaction(function () {
            $req = $this->selectedRequest;

            // 1. Catat Pengeluaran Uang
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'type' => 'expense',
                'amount' => $this->pay_amount,
                'wallet_id' => $this->wallet_id,
                'product_line_id' => $req->product->product_line_id, // Beban masuk ke divisi produk tsb
                'category' => 'Pembelian Bahan Baku',
                'transaction_date' => Carbon::now(),
                'notes' => 'Approval Request #' . $req->id . ' (' . $req->product->name . ')',
            ]);

            // 2. Kurangi Saldo Dompet
            Wallet::find($this->wallet_id)->decrement('balance', $this->pay_amount);

            // 3. Tambah Stok Gudang
            Product::find($req->product_id)->increment('current_stock', $req->quantity);

            // 4. Catat Log Stok
            InventoryLog::create([
                'product_id' => $req->product_id,
                'user_id' => Auth::id(),
                'type' => 'purchase_in', // Barang Masuk Beli
                'quantity' => $req->quantity,
                'date' => Carbon::now(),
                'notes' => 'Pembelian disetujui Keuangan'
            ]);

            // 5. Update Status Request
            $req->update(['status' => 'approved']);
        });

        session()->flash('message', 'Pembelian Disetujui & Stok Bertambah!');
        $this->reset(['selectedRequest', 'pay_amount']);
    }

    public function reject($id)
    {
        PurchaseRequest::where('id', $id)->update(['status' => 'rejected']);
    }

    public function render()
    {
        return view('livewire.finance.approval', [
            'requests' => PurchaseRequest::where('status', 'pending')->with('product', 'user')->get(),
            'wallets' => Wallet::all()
        ])->layout('layouts.app');
    }
}