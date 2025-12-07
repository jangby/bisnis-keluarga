<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\OfficeSetting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Machine extends Component
{
    public $latitude;
    public $longitude;
    public $error_message;
    public $distance; // Jarak user ke kantor (meter)
    public $is_within_radius = false;

    public function mount()
    {
        // Cek apakah sudah absen masuk/pulang hari ini untuk update UI
    }

    // Fungsi menghitung jarak (Haversine Formula)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c);
    }

    // Dipanggil oleh Javascript saat lokasi ditemukan
    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;

        $office = OfficeSetting::first();
        if (!$office) {
            $this->error_message = "Lokasi kantor belum diatur oleh Owner.";
            return;
        }

        $this->distance = $this->calculateDistance($lat, $lng, $office->latitude, $office->longitude);
        $this->is_within_radius = $this->distance <= $office->radius_meters;
    }

    public function attendance($type) // $type = 'in' atau 'out'
    {
        if (!$this->is_within_radius) {
            $this->dispatch('notify', message: 'Anda berada di luar jangkauan kantor!', type: 'error');
            return;
        }

        $user = Auth::user();
        $today = Carbon::today();
        
        // Cari record hari ini
        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', $today)
                                ->first();

        if ($type === 'in') {
            if ($attendance) {
                 $this->dispatch('notify', message: 'Anda sudah absen masuk hari ini.', type: 'warning');
                 return;
            }
            
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in' => now(),
                'lat_in' => $this->latitude,
                'long_in' => $this->longitude,
                'status' => 'hadir'
            ]);
            
            $this->dispatch('notify', message: 'Berhasil Absen Masuk!', type: 'success');
            
        } elseif ($type === 'out') {
            if (!$attendance) {
                $this->dispatch('notify', message: 'Anda belum absen masuk!', type: 'error');
                return;
            }
            
            $attendance->update([
                'clock_out' => now(),
                'lat_out' => $this->latitude,
                'long_out' => $this->longitude,
            ]);

            $this->dispatch('notify', message: 'Berhasil Absen Pulang. Hati-hati di jalan!', type: 'success');
        }
    }

    public function render()
    {
        // Ambil data absen hari ini untuk dikirim ke View
        $todayAttendance = Attendance::where('user_id', Auth::id())
                                     ->whereDate('date', Carbon::today())
                                     ->first();

        return view('livewire.attendance.machine', [
            'todayAttendance' => $todayAttendance,
            'office' => OfficeSetting::first()
        ]);
    }
}