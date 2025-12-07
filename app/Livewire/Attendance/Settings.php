<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\OfficeSetting;
use App\Models\Holiday;
use Livewire\WithPagination;

class Settings extends Component
{
    use WithPagination;

    // --- Properties Lokasi ---
    public $name, $latitude, $longitude, $radius_meters;

    // --- Properties Hari Libur ---
    public $holiday_date;
    public $holiday_desc;

    public function mount()
    {
        // Load Data Lokasi
        $setting = OfficeSetting::first();
        if ($setting) {
            $this->name = $setting->name;
            $this->latitude = $setting->latitude;
            $this->longitude = $setting->longitude;
            $this->radius_meters = $setting->radius_meters;
        }
    }

    // Fungsi Simpan Lokasi
    public function saveLocation()
    {
        $this->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_meters' => 'required|integer|min:5',
            'name' => 'required|string|max:255',
        ]);

        OfficeSetting::updateOrCreate(
            ['id' => 1],
            [
                'name' => $this->name,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius_meters' => $this->radius_meters
            ]
        );

        $this->dispatch('notify', message: 'Lokasi kantor diperbarui!', type: 'success');
    }

    // Fungsi Tambah Libur
    public function addHoliday()
    {
        $this->validate([
            'holiday_date' => 'required|date|unique:holidays,date',
            'holiday_desc' => 'required|string|max:255',
        ], [
            'holiday_date.unique' => 'Tanggal ini sudah ada di daftar libur.',
        ]);

        Holiday::create([
            'date' => $this->holiday_date,
            'description' => $this->holiday_desc,
        ]);

        // Reset Form
        $this->holiday_date = '';
        $this->holiday_desc = '';

        $this->dispatch('notify', message: 'Hari libur berhasil ditambahkan!', type: 'success');
    }

    // Fungsi Hapus Libur
    public function deleteHoliday($id)
    {
        Holiday::destroy($id);
        $this->dispatch('notify', message: 'Hari libur dihapus.', type: 'warning');
    }

    public function render()
    {
        // Ambil data libur yang akan datang & terbaru
        $holidays = Holiday::orderBy('date', 'desc')->paginate(5);

        return view('livewire.attendance.settings', [
            'holidays' => $holidays
        ])->layout('layouts.app');
    }
}