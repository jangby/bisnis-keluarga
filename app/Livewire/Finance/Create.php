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
    // ==========================================
    // PROPERTI FORM
    // ==========================================
    public $type = 'expense'; // Default: Pengeluaran
    
    // Input Data Utama
    public $amount;
    public $category;
    public $date;
    public $notes;
    
    // Relasi
    public $wallet_id;
    public $product_line_id;
    public $contact_id;

    // Metode Pembayaran & Hutang
    public $payment_method = 'cash'; // Pilihan: cash, transfer, debt
    public $due_date; // Wajib diisi jika payment_method = debt

    // ==========================================
    // LIFECYCLE: MOUNT (Saat halaman dimuat)
    // ==========================================
    public function mount($type = null)
    {
        // Set tanggal hari ini
        $this->date = Carbon::now()->format('Y-m-d');
        
        // Set default pilihan pertama agar user tidak repot
        $this->wallet_id = Wallet::first()->id ?? null;
        $this->product_line_id = ProductLine::first()->id ?? null;
        
        // Tangkap parameter dari URL Dashboard (misal: ?type=income)
        if ($type) {
            $this->type = $type;
        }
        
        // Set kategori default agar dropdown tidak kosong
        $this->category = $this->type == 'income' ? 'Pendapatan Jasa' : 'Operasional Umum';
    }

    // ==========================================
    // COMPUTED PROPERTIES (Logika Dinamis)
    // ==========================================

    // Daftar Kategori (Disesuaikan agar tidak tumpang tindih dengan Kasir/Produksi)
    public function getCategoriesProperty()
    {
        if ($this->type == 'income') {
            return [
                'Pendapatan Jasa', 
                'Komisi/Fee', 
                'Pendapatan Lain-lain', 
                'Penerimaan Piutang', 
                'Suntikan Modal', 
                'Hibah', 
                'Pinjaman Bank'
            ];
        } else {
            return [
                'Operasional Umum', // Listrik, Air, Internet
                'Beban Gaji', 
                'Beban Sewa/Gedung', 
                'Biaya Kemasan', 
                'Beban Ongkos Kirim', 
                'Beban Iklan/Promosi', 
                'Perlengkapan Kantor', 
                'Peralatan/Mesin', 
                'Investasi', 
                'Pembayaran Utang', 
                'Pengeluaran Pribadi (Prive)', 
                'Sedekah/Sosial', 
                'Beban Lain-lain'
            ];
        }
    }

    // Helper untuk tampilan (disederhanakan jadi false karena stok dihapus dari sini)
    public function getNeedsProductInputProperty()
    {
        return false; 
    }

    // ==========================================
    // LOGIKA PENYIMPANAN (SAVE)
    // ==========================================
    public function save()
    {
        // 1. Validasi Input
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'category' => 'required',
            'product_line_id' => 'required|exists:product_lines,id',
            // Jika hutang, wajib isi tanggal jatuh tempo
            'due_date' => $this->payment_method == 'debt' ? 'required|date' : 'nullable',
            // Jika bukan hutang, wajib pilih dompet sumber dana
            'wallet_id' => $this->payment_method != 'debt' ? 'required|exists:wallets,id' : 'nullable',
        ]);

        DB::transaction(function () {
            
            // 2. Simpan Record Keuangan Utama
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'type' => $this->type,
                'amount' => $this->amount,
                // Jika hutang, wallet_id bisa null atau pakai dummy, di sini kita pakai wallet pertama sebagai placeholder
                'wallet_id' => $this->payment_method == 'debt' ? Wallet::first()->id : $this->wallet_id,
                'product_line_id' => $this->product_line_id,
                'contact_id' => $this->contact_id,
                'category' => $this->category,
                'transaction_date' => $this->date,
                'notes' => $this->notes,
                'payment_method' => $this->payment_method,
                'due_date' => $this->due_date,
                // Jika hutang, berarti belum lunas (is_paid = false)
                'is_paid' => $this->payment_method != 'debt',
            ]);

            // 3. Logika Hutang / Piutang
            if ($this->payment_method == 'debt') {
                // Simpan ke tabel Hutang (Debts) untuk dipantau
                Debt::create([
                    'contact_id' => $this->contact_id ?? Contact::firstOrCreate(['name' => 'Umum'])->id,
                    'product_line_id' => $this->product_line_id,
                    // Jika Expense & Hutang = Payable (Utang Dagang)
                    // Jika Income & Hutang = Receivable (Piutang)
                    'type' => $this->type == 'expense' ? 'payable' : 'receivable',
                    'amount' => $this->amount,
                    'remaining' => $this->amount, // Sisa hutang di awal sama dengan total
                    'due_date' => $this->due_date,
                    'status' => 'unpaid'
                ]);
            } else {
                // 4. Logika Update Saldo Dompet (Hanya jika Tunai/Transfer)
                $wallet = Wallet::find($this->wallet_id);
                if ($this->type == 'income') {
                    $wallet->increment('balance', $this->amount);
                } else {
                    $wallet->decrement('balance', $this->amount);
                }
            }

        });

        // 5. Feedback & Redirect
        session()->flash('message', 'Transaksi berhasil disimpan!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.finance.create', [
            'wallets' => Wallet::all(),
            'productLines' => ProductLine::all(),
            'contacts' => Contact::orderBy('name')->get(),
            'categories' => $this->categories // Memanggil computed property
        ])->layout('layouts.app');
    }
}