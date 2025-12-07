<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance;
use Carbon\Carbon;

class Monitoring extends Component
{
    use WithPagination;

    public $dateFilter;

    public function mount()
    {
        $this->dateFilter = Carbon::today()->format('Y-m-d');
    }

    public function render()
{
    $attendances = Attendance::with('user')
        ->whereDate('date', $this->dateFilter)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Tambahkan ->layout('layouts.app') di sini juga
    return view('livewire.attendance.monitoring', [
        'attendances' => $attendances
    ])->layout('layouts.app');
}
}