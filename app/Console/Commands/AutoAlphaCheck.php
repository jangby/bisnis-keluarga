<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use Carbon\Carbon;

class AutoAlphaCheck extends Command
{
    // Nama command yang nanti dipanggil
    protected $signature = 'app:auto-alpha';

    // Deskripsi command
    protected $description = 'Mengecek karyawan yang tidak absen kemarin dan menandainya sebagai Alpha';

    public function handle()
    {
        // 1. Target adalah HARI KEMARIN (Karena job jalan jam 00:01 hari ini)
        $yesterday = Carbon::yesterday(); 

        $this->info('Memulai pengecekan Auto-Alpha untuk tanggal: ' . $yesterday->format('Y-m-d'));

        // 2. Cek apakah kemarin HARI LIBUR?
        $isHoliday = Holiday::whereDate('date', $yesterday)->exists();
        
        // Cek juga apakah kemarin hari Minggu (Optional: Jika kantor libur minggu)
        // $isSunday = $yesterday->isSunday(); 

        if ($isHoliday) { 
            $this->info('Kemarin adalah hari libur nasional. Proses dibatalkan.');
            return;
        }

        // 3. Ambil semua User KECUALI Pelanggan
        // Asumsi: Role selain 'pelanggan' adalah internal (owner, staf, dll)
        $employees = User::where('role', '!=', 'pelanggan')->get();

        $alphaCount = 0;

        foreach ($employees as $emp) {
            
            // A. Cek Absensi: Apakah sudah ada record di tabel attendance?
            $hasAttendance = Attendance::where('user_id', $emp->id)
                                       ->whereDate('date', $yesterday)
                                       ->exists();

            if ($hasAttendance) {
                continue; // Skip, dia sudah absen (hadir/sakit/izin via mesin)
            }

            // B. Cek Izin Resmi: Apakah ada pengajuan izin yang APPROVED?
            $hasLeave = LeaveRequest::where('user_id', $emp->id)
                                    ->where('status', 'approved')
                                    ->whereDate('start_date', '<=', $yesterday)
                                    ->whereDate('end_date', '>=', $yesterday)
                                    ->exists();

            if ($hasLeave) {
                // Opsional: Anda bisa otomatis buat record 'izin' di attendance jika mau,
                // tapi biasanya dibiarkan kosong atau handle terpisah.
                // Di sini kita skip agar tidak kena Alpha.
                continue; 
            }

            // C. EKSEKUSI ALPHA
            // Jika tidak absen & tidak izin -> ALPHA
            Attendance::create([
                'user_id'   => $emp->id,
                'date'      => $yesterday,
                'status'    => 'alpha', // Pastikan enum di database support 'alpha'
                'note'      => 'Sistem Auto-Alpha (Tidak Absen)',
                'clock_in'  => null,
                'clock_out' => null
            ]);

            $this->info("User {$emp->name} ditandai Alpha.");
            $alphaCount++;
        }

        $this->info("Selesai. Total karyawan Alpha: {$alphaCount}");
    }
}