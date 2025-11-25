<?php

namespace App\Livewire\Finance;

use App\Models\Wallet;
use App\Models\ProductLine;
use App\Models\FinanceRecord;
use App\Models\Contact;
use App\Models\Debt;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Create extends Component
{
    public $type = 'expense'; 
    
    // Input Data
    public $amount;
    public $category;
    public $date;
    public $notes;
    
    // Relasi
    public $wallet_id;
    public $product_line_id;
    public $contact_id;

    // Pembayaran
    public $payment_method = 'cash';
    public $due_date;

    // [BARU] State untuk Modal
    public $showConfirmationModal = false;

    public function mount($type = null)
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->wallet_id = Wallet::first()->id ?? null;
        $this->product_line_id = ProductLine::first()->id ?? null;
        
        if ($type) $this->type = $type;
        $this->category = $this->type == 'income' ? 'Pendapatan Jasa' : 'Operasional Umum';
    }

    public function getCategoriesProperty()
    {
        if ($this->type == 'income') {
            return [
                'Pendapatan Jasa', 'Penjualan Produk', 'Komisi/Fee', 
                'Pendapatan Lain-lain', 'Penerimaan Piutang', 
                'Suntikan Modal', 'Hibah', 'Pinjaman Bank'
            ];
        } else {
            return [
                'Operasional Umum', 'Belanja Bahan Baku', 'Beban Gaji', 
                'Beban Sewa', 'Listrik & Air', 'Internet & Pulsa',
                'Transportasi', 'Pemasaran/Iklan', 'Perbaikan/Maintenance',
                'Perlengkapan Kantor', 'Investasi Alat', 'Pembayaran Utang', 
                'Prive (Tarik Tunai)', 'Sedekah/Sosial', 'Beban Gedung', 'Biaya Kemasan', 'Penyusutan Gedung', 'Penyusutan Peralatan','Beban Lain-lain'
            ];
        }
    }

    // [BARU] Fungsi Validasi sebelum Buka Modal
    public function confirmSave()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'category' => 'required',
            'product_line_id' => 'required',
            'due_date' => $this->payment_method == 'debt' ? 'required|date' : 'nullable',
            'wallet_id' => $this->payment_method != 'debt' ? 'required' : 'nullable',
        ]);

        // Jika valid, buka modal
        $this->showConfirmationModal = true;
    }

    public function save()
    {
        // Validasi ulang (security)
        $this->validate([ 'amount' => 'required|numeric|min:1' ]);

        DB::transaction(function () {
            // 1. Catat Record
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'type' => $this->type,
                'amount' => $this->amount,
                'wallet_id' => $this->payment_method == 'debt' ? Wallet::first()->id : $this->wallet_id,
                'product_line_id' => $this->product_line_id,
                'contact_id' => $this->contact_id,
                'category' => $this->category,
                'transaction_date' => $this->date,
                'notes' => $this->notes,
                'payment_method' => $this->payment_method,
                'due_date' => $this->due_date,
                'is_paid' => $this->payment_method != 'debt',
            ]);

            // 2. Handle Hutang/Piutang atau Saldo
            if ($this->payment_method == 'debt') {
                Debt::create([
                    'contact_id' => $this->contact_id ?? Contact::firstOrCreate(['name' => 'Umum', 'type' => 'customer'])->id,
                    'product_line_id' => $this->product_line_id,
                    'type' => $this->type == 'expense' ? 'payable' : 'receivable',
                    'amount' => $this->amount,
                    'remaining' => $this->amount,
                    'due_date' => $this->due_date,
                    'status' => 'unpaid',
                    'notes' => $this->notes
                ]);
            } else {
                $wallet = Wallet::find($this->wallet_id);
                if ($this->type == 'income') {
                    $wallet->increment('balance', $this->amount);
                } else {
                    $wallet->decrement('balance', $this->amount);
                }
            }
        });

        // [UPDATE] Reset form & Tutup Modal (Tanpa Redirect)
        $this->reset(['amount', 'notes']);
        $this->showConfirmationModal = false;
        
        session()->flash('message', 'Transaksi berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.finance.create', [
            'wallets' => Wallet::all(),
            'productLines' => ProductLine::all(),
            'contacts' => Contact::orderBy('name')->get(),
            'categories' => $this->categories
        ])->layout('layouts.app');
    }
}