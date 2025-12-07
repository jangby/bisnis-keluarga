<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload file
use App\Models\LeaveRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Pastikan GD extension aktif di PHP
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LeaveForm extends Component
{
    use WithFileUploads;

    public $start_date;
    public $end_date;
    public $type = 'sakit'; // default
    public $reason;
    public $photo; // File temporary

    protected $rules = [
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'type'       => 'required|in:sakit,izin,cuti',
        'reason'     => 'required|string|max:500',
        'photo'      => 'nullable|image|max:5120', // Max 5MB
    ];

    public function submit()
    {
        $this->validate();

        $proofPath = null;

        if ($this->photo) {
            // 1. Buat nama file unik .webp
            $filename = 'leave_' . time() . '_' . Auth::id() . '.webp';
            $path = 'leaves/' . $filename;

            // 2. Proses Kompresi menggunakan Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($this->photo->getRealPath());
            
            // Resize jika terlalu besar (opsional, misal max width 800px)
            $image->scale(width: 800);

            // Encode ke webp dengan kualitas 75%
            $encoded = $image->toWebp(quality: 75);

            // 3. Simpan ke storage (storage/app/public/leaves)
            Storage::disk('public')->put($path, (string) $encoded);
            
            $proofPath = $path;
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'type' => $this->type,
            'reason' => $this->reason,
            'proof_path' => $proofPath,
            'status' => 'pending' // Menunggu approval owner
        ]);

        $this->reset();
        $this->dispatch('notify', message: 'Pengajuan berhasil dikirim!', type: 'success');
        
        // Redirect atau close modal bisa ditambahkan di sini
    }

    public function render()
    {
        return view('livewire.attendance.leave-form');
    }
}