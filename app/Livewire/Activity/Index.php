<?php

namespace App\Livewire\Activity;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Jangan lupa import ini

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $userId = '';
    public $date = '';

    // [UPDATE] Pengecekan Keamanan
    public function mount()
    {
        // Pastikan 'inventory' ada di sini
        if (!in_array(Auth::user()->role, ['owner', 'inventory'])) {
            abort(403, 'Akses Ditolak.');
        }
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('subject_type', 'like', '%' . $this->search . '%');
            })
            ->when($this->userId, function($q) {
                $q->where('user_id', $this->userId);
            })
            ->when($this->date, function($q) {
                $q->whereDate('created_at', $this->date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.activity.index', [
            'logs' => $logs,
            'users' => User::all()
        ])->layout('layouts.app');
    }
}