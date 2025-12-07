<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class Index extends Component
{
    public $month;
    public $year;
    
    // Variabel untuk menampung data slip yang sedang dibuka
    public $slipData = null; 
    public $showSlipModal = false;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    // Fungsi untuk membuka slip gaji spesifik
    public function openSlip($userId)
    {
        $user = User::find($userId);
        
        if(!$user) return;

        // Hitung Kehadiran
        $presentCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where('status', 'hadir')
            ->count();

        $dailySalary = $user->daily_salary ?? 0;
        $totalSalary = $presentCount * $dailySalary;

        // Simpan ke variabel public agar bisa dibaca di View
        $this->slipData = [
            'user' => $user,
            'period' => Carbon::createFromDate($this->year, $this->month, 1)->translatedFormat('F Y'),
            'print_date' => Carbon::now()->translatedFormat('d F Y'),
            'present_count' => $presentCount,
            'daily_salary' => $dailySalary,
            'total_salary' => $totalSalary,
        ];

        $this->showSlipModal = true;
    }

    public function closeSlip()
    {
        $this->showSlipModal = false;
        $this->slipData = null;
    }

    public function render()
    {
        // Query Daftar Karyawan (Sama seperti sebelumnya)
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
            'payrollData' => $payrollData
        ])->layout('layouts.app');
    }
}