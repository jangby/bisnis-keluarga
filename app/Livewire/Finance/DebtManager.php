<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\Debt;
use App\Models\Wallet;
use App\Models\FinanceRecord;
use App\Models\ProductLine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebtManager extends Component
{
    public $activeTab = 'receivable'; // 'receivable' (Orang Utang ke Kita) | 'payable' (Kita Utang ke Supplier)
    
    // Variabel untuk Modal Bayar
    public $selectedDebt;
    public $paymentAmount;
    public $wallet_id;
    public $showPaymentModal = false;

    public function mount()
    {
        // Default dompet pertama
        $this->wallet_id = Wallet::first()->id ?? null;
    }

    // Buka Modal Bayar
    public function selectDebt($id)
    {
        $this->selectedDebt = Debt::with('contact')->find($id);
        // Default isi form dengan sisa utang (Langsung Lunas)
        $this->paymentAmount = $this->selectedDebt->remaining; 
        $this->showPaymentModal = true;
    }

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1|max:' . $this->selectedDebt->remaining,
            'wallet_id' => 'required'
        ]);

        DB::transaction(function () {
            $debt = $this->selectedDebt;
            
            // 1. Update Sisa Utang
            $debt->remaining -= $this->paymentAmount;
            
            // Cek Lunas?
            if ($debt->remaining <= 0) {
                $debt->status = 'paid';
                $debt->remaining = 0;
            } else {
                $debt->status = 'partial';
            }
            $debt->save();

            // 2. Tentukan Arah Uang (Masuk/Keluar)
            // Jika PIUTANG (Receivable) -> Uang Masuk (Income)
            // Jika UTANG (Payable) -> Uang Keluar (Expense)
            $type = $debt->type == 'receivable' ? 'income' : 'expense';
            $catName = $debt->type == 'receivable' ? 'Pelunasan Piutang' : 'Pembayaran Utang';
            
            // Ambil ID Divisi Umum (biasanya ID 1 atau terakhir, sesuaikan logic jika perlu)
            $generalLineId = ProductLine::first()->id; 

            // 3. Catat di Buku Keuangan
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'product_line_id' => $generalLineId, // Masuk ke kas umum
                'type' => $type,
                'amount' => $this->paymentAmount,
                'category' => $catName,
                'transaction_date' => Carbon::now(),
                'notes' => 'Setoran dari ' . $debt->contact->name . ' (Sisa: ' . number_format($debt->remaining) . ')'
            ]);

            // 4. Update Saldo Fisik Dompet
            $wallet = Wallet::find($this->wallet_id);
            if ($type == 'income') {
                $wallet->increment('balance', $this->paymentAmount);
            } else {
                $wallet->decrement('balance', $this->paymentAmount);
            }
        });

        $this->showPaymentModal = false;
        $this->reset(['selectedDebt', 'paymentAmount']);
        session()->flash('message', 'Pembayaran berhasil dicatat! Saldo dompet bertambah.');
    }

    public function render()
    {
        // Ambil data utang yang BELUM LUNAS
        $debts = Debt::with('contact')
            ->where('type', $this->activeTab)
            ->whereIn('status', ['unpaid', 'partial']) 
            ->orderBy('due_date', 'asc') // Yang mau jatuh tempo duluan diatas
            ->get();

        return view('livewire.finance.debt-manager', [
            'debts' => $debts,
            'wallets' => Wallet::all()
        ])->layout('layouts.app');
    }
}