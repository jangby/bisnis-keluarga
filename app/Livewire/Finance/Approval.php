<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\FinanceRecord;
use App\Models\ProductionRequest; // Import Model Baru
use Illuminate\Support\Facades\Auth;

class Approval extends Component
{
    public function approve($id)
    {
        // Logika Approval Keuangan (Lama)
        $record = FinanceRecord::find($id);
        if ($record) {
            $record->update(['status' => 'approved']);
            // Jika expense, kurangi saldo dompet
            if ($record->type == 'expense' && $record->wallet_id) {
                $wallet = \App\Models\Wallet::find($record->wallet_id);
                if ($wallet) $wallet->decrement('balance', $record->amount);
            }
            // Jika income, tambah saldo
            elseif ($record->type == 'income' && $record->wallet_id) {
                $wallet = \App\Models\Wallet::find($record->wallet_id);
                if ($wallet) $wallet->increment('balance', $record->amount);
            }
            
            $this->dispatch('show-toast', type: 'success', message: 'Transaksi Keuangan Disetujui');
        }
    }

    public function reject($id)
    {
        FinanceRecord::where('id', $id)->update(['status' => 'rejected']);
        $this->dispatch('show-toast', type: 'error', message: 'Transaksi Ditolak');
    }

    // --- TAMBAHAN: LOGIKA APPROVAL REQUEST BAHAN ---
    
    public function approveMaterial($id)
    {
        $req = ProductionRequest::find($id);
        if ($req) {
            $req->update(['status' => 'approved']);
            
            // Opsional: Anda bisa otomatis buat Finance Record (Expense) disini jika mau
            // Tapi untuk sekarang kita tandai saja sebagai 'approved' agar bisa dibelanjakan
            
            $this->dispatch('show-toast', type: 'success', message: 'Request Bahan Disetujui! Silakan proses pembelian.');
        }
    }

    public function rejectMaterial($id)
    {
        ProductionRequest::where('id', $id)->update(['status' => 'rejected']);
        $this->dispatch('show-toast', type: 'error', message: 'Request Bahan Ditolak.');
    }

    public function render()
    {
        // 1. Ambil Pengajuan Keuangan
        $financeRequests = FinanceRecord::with(['user', 'product_line'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Ambil Pengajuan Stok Bahan (BARU)
        $materialRequests = ProductionRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('requested_at', 'desc')
            ->get();

        return view('livewire.finance.approval', [
            'requests' => $financeRequests,
            'materialRequests' => $materialRequests // Kirim ke view
        ])->layout('layouts.app');
    }
}