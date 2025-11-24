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
    public $activeTab = 'receivable'; // 'receivable' (Piutang) | 'payable' (Utang)
    
    // Variabel Modal Bayar
    public $selectedDebt;
    public $paymentAmount;
    public $wallet_id;
    public $showPaymentModal = false;

    public function mount()
    {
        $this->wallet_id = Wallet::first()->id ?? null;
    }

    public function selectDebt($id)
    {
        $this->selectedDebt = Debt::with('contact')->find($id);
        $this->paymentAmount = $this->selectedDebt->remaining; // Default langsung lunas
        $this->showPaymentModal = true;
    }

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1|max:' . ($this->selectedDebt->remaining + 100), // +100 toleransi pembulatan
            'wallet_id' => 'required'
        ]);

        DB::transaction(function () {
            $debt = $this->selectedDebt;
            
            // 1. Kurangi Hutang
            $debt->remaining -= $this->paymentAmount;
            
            if ($debt->remaining <= 0) {
                $debt->status = 'paid';
                $debt->remaining = 0;
            } else {
                $debt->status = 'partial';
            }
            $debt->save();

            // 2. Catat Keuangan
            // Receivable (Piutang) -> Uang Masuk (Income)
            // Payable (Utang) -> Uang Keluar (Expense)
            $type = $debt->type == 'receivable' ? 'income' : 'expense';
            $catName = $debt->type == 'receivable' ? 'Pelunasan Piutang' : 'Pembayaran Utang';
            $generalLineId = ProductLine::first()->id ?? null; 

            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $this->wallet_id,
                'product_line_id' => $generalLineId,
                'type' => $type,
                'amount' => $this->paymentAmount,
                'category' => $catName,
                'transaction_date' => Carbon::now(),
                'notes' => 'Setoran: ' . $debt->contact->name
            ]);

            // 3. Update Saldo Dompet
            $wallet = Wallet::find($this->wallet_id);
            if ($type == 'income') {
                $wallet->increment('balance', $this->paymentAmount);
            } else {
                $wallet->decrement('balance', $this->paymentAmount);
            }
        });

        $this->showPaymentModal = false;
        $this->reset(['selectedDebt', 'paymentAmount']);
        session()->flash('message', 'Pembayaran Berhasil Dicatat!');
    }

    public function render()
    {
        // Ambil data yang belum lunas
        $debts = Debt::with('contact')
            ->where('type', $this->activeTab)
            ->whereIn('status', ['unpaid', 'partial']) 
            ->orderBy('due_date', 'asc') // Urutkan berdasarkan jatuh tempo terdekat
            ->get();

        return view('livewire.finance.debt-manager', [
            'debts' => $debts,
            'wallets' => Wallet::all()
        ])->layout('layouts.app');
    }
}