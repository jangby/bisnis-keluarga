<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Debt;
use App\Models\FinanceRecord;
use App\Models\Wallet; // Import Wallet
use App\Models\ProductLine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $month;
    public $year;
    
    // Variabel Modal Slip
    public $slipData = null; 
    public $showSlipModal = false;
    
    // Variabel Pembayaran
    public $kasbon_deduction = 0; 
    public $total_kasbon = 0; 
    public $selected_wallet_id; // <--- [BARU] Untuk pilih dompet

    // Variabel Kasbon Baru
    public $showKasbonModal = false;
    public $new_kasbon_user_id;
    public $new_kasbon_amount;
    public $new_kasbon_notes;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        
        // Default ke dompet pertama
        $this->selected_wallet_id = Wallet::first()->id ?? null;
    }

    public function openSlip($userId)
    {
        $user = User::find($userId);
        if(!$user) return;

        // Hitung Gaji
        $presentCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where('status', 'hadir')
            ->count();

        $dailySalary = $user->daily_salary ?? 0;
        $grossSalary = $presentCount * $dailySalary;

        // Cek Kasbon
        $this->total_kasbon = $user->current_debt; 
        $this->kasbon_deduction = 0;

        // Reset pilihan dompet ke default saat buka baru
        $this->selected_wallet_id = Wallet::first()->id ?? null;

        $this->slipData = [
            'user' => $user,
            'period' => Carbon::createFromDate($this->year, $this->month, 1)->translatedFormat('F Y'),
            'print_date' => Carbon::now()->translatedFormat('d F Y'),
            'present_count' => $presentCount,
            'daily_salary' => $dailySalary,
            'gross_salary' => $grossSalary,
        ];

        $this->showSlipModal = true;
    }

    public function processPayment()
    {
        $this->validate([
            'kasbon_deduction' => 'numeric|min:0|max:' . min($this->slipData['gross_salary'], $this->total_kasbon),
            'selected_wallet_id' => 'required|exists:wallets,id', // <--- [BARU] Validasi dompet
        ], [
            'kasbon_deduction.max' => 'Potongan tidak boleh melebihi Gaji atau Sisa Utang.',
            'selected_wallet_id.required' => 'Pilih sumber dana pembayaran.',
        ]);

        DB::transaction(function () {
            $user = $this->slipData['user'];
            $totalPay = $this->slipData['gross_salary'] - $this->kasbon_deduction;
            
            // [BARU] Ambil dompet sesuai pilihan user
            $wallet = Wallet::find($this->selected_wallet_id); 
            $generalLineId = ProductLine::first()->id ?? 1;

            // 1. Catat Pengeluaran Gaji
            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id, // Gunakan ID dompet yang dipilih
                'product_line_id' => $generalLineId,
                'type' => 'expense',
                'amount' => $totalPay,
                'category' => 'Gaji Karyawan',
                'notes' => "Gaji {$user->name} ({$this->slipData['period']})",
                'transaction_date' => now(),
            ]);
            
            // Kurangi Saldo Dompet yang Dipilih
            $wallet->decrement('balance', $totalPay);

            // 2. Proses Potong Kasbon (Sama seperti sebelumnya)
            if ($this->kasbon_deduction > 0) {
                $debts = $user->debts()
                    ->where('type', 'receivable')
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                $remainingToDeduct = $this->kasbon_deduction;

                foreach ($debts as $debt) {
                    if ($remainingToDeduct <= 0) break;
                    $take = min($debt->remaining, $remainingToDeduct);
                    $debt->remaining -= $take;
                    $debt->status = ($debt->remaining <= 0) ? 'paid' : 'partial';
                    $debt->save();
                    $remainingToDeduct -= $take;
                }
            }
        });

        $this->dispatch('trigger-print');
        $this->dispatch('notify', message: 'Gaji berhasil dicatat!', type: 'success');
    }

    // --- FITUR KASBON BARU ---
    public function openKasbonModal()
    {
        $this->reset(['new_kasbon_user_id', 'new_kasbon_amount', 'new_kasbon_notes']);
        // Default wallet
        $this->selected_wallet_id = Wallet::first()->id ?? null;
        $this->showKasbonModal = true;
    }

    public function saveKasbon()
    {
        $this->validate([
            'new_kasbon_user_id' => 'required|exists:users,id',
            'new_kasbon_amount' => 'required|numeric|min:1000',
            'new_kasbon_notes' => 'required|string',
            'selected_wallet_id' => 'required|exists:wallets,id', // [BARU]
        ]);

        DB::transaction(function () {
            $generalLineId = ProductLine::first()->id ?? 1;
            
            // [BARU] Ambil dompet pilihan
            $wallet = Wallet::find($this->selected_wallet_id);

            Debt::create([
                'user_id' => Auth::id(),
                'employee_id' => $this->new_kasbon_user_id,
                'type' => 'receivable',
                'amount' => $this->new_kasbon_amount,
                'remaining' => $this->new_kasbon_amount,
                'due_date' => now()->addMonth(),
                'status' => 'unpaid',
                'description' => $this->new_kasbon_notes,
                'name' => User::find($this->new_kasbon_user_id)->name 
            ]);

            FinanceRecord::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id, // Pakai dompet pilihan
                'product_line_id' => $generalLineId,
                'type' => 'expense',
                'amount' => $this->new_kasbon_amount,
                'category' => 'Kasbon Karyawan',
                'notes' => 'Kasbon: ' . $this->new_kasbon_notes,
                'transaction_date' => now(),
            ]);
            
            // Kurangi saldo dompet pilihan
            $wallet->decrement('balance', $this->new_kasbon_amount);
        });

        $this->showKasbonModal = false;
        $this->dispatch('notify', message: 'Kasbon berhasil dicatat.', type: 'success');
    }

    public function closeSlip()
    {
        $this->showSlipModal = false;
        $this->slipData = null;
    }

    public function render()
    {
        $employees = User::whereIn('role', ['owner','finance', 'marketing', 'production', 'staff'])
            ->orderBy('name', 'asc')
            ->get();

        $payrollData = $employees->map(function ($user) {
            $presentCount = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->where('status', 'hadir') 
                ->count();

            $totalSalary = $presentCount * ($user->daily_salary ?? 0);

            return [
                'user' => $user,
                'present_count' => $presentCount,
                'daily_salary' => $user->daily_salary ?? 0,
                'total_salary' => $totalSalary
            ];
        });

        return view('livewire.payroll.index', [
            'payrollData' => $payrollData,
            'allEmployees' => $employees,
            'wallets' => Wallet::all() // [BARU] Kirim data wallets ke view
        ])->layout('layouts.app');
    }
}