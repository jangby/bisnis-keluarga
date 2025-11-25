<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FinanceRecord;
use App\Models\ProductLine;
use App\Models\Wallet; // Tambahkan ini
use App\Models\Contact; // Tambahkan ini
use Carbon\Carbon;

class History extends Component
{
    use WithPagination;

    // Filter Properties
    public $dateStart, $dateEnd, $type = 'all', $lineId = 'all', $search = '';

    // [BARU] Properties untuk Edit
    public $editingRecordId = null;
    public $editAmount, $editCategory, $editDate, $editNotes, $editLineId, $editWalletId, $editContactId;
    public $showEditModal = false;

    public function mount()
    {
        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->format('Y-m-d');
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'type', 'lineId', 'dateStart', 'dateEnd'])) {
            $this->resetPage();
        }
    }

    // --- [BARU] FUNGSI EDIT ---

    public function edit($id)
    {
        $record = FinanceRecord::find($id);
        
        if ($record) {
            $this->editingRecordId = $record->id;
            $this->editAmount = $record->amount;
            $this->editCategory = $record->category;
            $this->editDate = $record->transaction_date->format('Y-m-d');
            $this->editNotes = $record->notes;
            $this->editLineId = $record->product_line_id;
            $this->editWalletId = $record->wallet_id;
            $this->editContactId = $record->contact_id;
            
            $this->showEditModal = true;
        }
    }

    public function update()
    {
        $this->validate([
            'editAmount' => 'required|numeric|min:1',
            'editCategory' => 'required',
            'editDate' => 'required|date',
            'editLineId' => 'required',
        ]);

        $record = FinanceRecord::find($this->editingRecordId);

        if ($record) {
            // Logic Update Saldo Wallet jika Nominal atau Wallet Berubah
            // Ini agak tricky: Kita kembalikan saldo lama, lalu potong saldo baru
            
            // 1. Revert (Balikin) Saldo Lama
            $oldWallet = Wallet::find($record->wallet_id);
            if ($record->type == 'income') {
                $oldWallet->decrement('balance', $record->amount);
            } else {
                $oldWallet->increment('balance', $record->amount);
            }

            // 2. Update Data Record
            $record->update([
                'amount' => $this->editAmount,
                'category' => $this->editCategory,
                'transaction_date' => $this->editDate,
                'notes' => $this->editNotes,
                'product_line_id' => $this->editLineId,
                'wallet_id' => $this->editWalletId, // Jika wallet diganti
                'contact_id' => $this->editContactId,
            ]);

            // 3. Apply (Terapkan) Saldo Baru
            $newWallet = Wallet::find($this->editWalletId ?? $record->wallet_id);
            if ($record->type == 'income') {
                $newWallet->increment('balance', $this->editAmount);
            } else {
                $newWallet->decrement('balance', $this->editAmount);
            }

            $this->showEditModal = false;
            session()->flash('message', 'Transaksi berhasil diperbarui!');
        }
    }

    public function delete($id)
    {
        $record = FinanceRecord::find($id);
        if ($record) {
            // Balikin Saldo sebelum hapus
            $wallet = Wallet::find($record->wallet_id);
            if ($record->type == 'income') {
                $wallet->decrement('balance', $record->amount);
            } else {
                $wallet->increment('balance', $record->amount);
            }
            
            $record->delete();
            session()->flash('message', 'Transaksi dihapus & saldo dikembalikan.');
        }
    }

    public function render()
    {
        // Query Render Tetap Sama
        $query = FinanceRecord::with('product_line', 'contact')
            ->whereBetween('transaction_date', [$this->dateStart, $this->dateEnd])
            ->when($this->type != 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->lineId != 'all', fn($q) => $q->where('product_line_id', $this->lineId))
            ->when($this->search, function ($q) {
                $q->where(function($sub) {
                    $sub->where('category', 'like', '%' . $this->search . '%')
                        ->orWhere('notes', 'like', '%' . $this->search . '%')
                        ->orWhere('amount', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        $summaryIncome = (clone $query)->where('type', 'income')->sum('amount');
        $summaryExpense = (clone $query)->where('type', 'expense')->sum('amount');

        return view('livewire.finance.history', [
            'transactions' => $query->paginate(20),
            'productLines' => ProductLine::all(),
            'wallets' => Wallet::all(), // Kirim data wallet untuk dropdown edit
            'contacts' => Contact::all(), // Kirim data kontak
            'summaryIncome' => $summaryIncome,
            'summaryExpense' => $summaryExpense
        ])->layout('layouts.app');
    }
}