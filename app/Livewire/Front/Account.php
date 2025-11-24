<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class Account extends Component
{
    // Variabel untuk Tab Navigasi di halaman akun
    public $activeTab = 'history'; // 'history' atau 'profile'

    // Form Edit Profil
    public $name, $email, $phone, $address;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            // $this->phone = $user->phone; // Aktifkan jika sudah ada kolom phone di tabel users
            // $this->address = $user->address; // Aktifkan jika sudah ada kolom address
        }
    }

    public function render()
    {
        $orders = [];
        
        if (Auth::check()) {
            // Ambil riwayat pesanan user yang sedang login
            $orders = Order::with('items')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('livewire.front.account', [
            'orders' => $orders
        ])->layout('layouts.front');
    }

    // Fungsi Logout untuk Pengunjung
    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('front.index'), navigate: true);
    }

    // Fungsi Update Profil (Opsional)
    public function updateProfile()
    {
        $user = Auth::user();
        // Validasi dan simpan...
        // $user->update([...]);
        session()->flash('message', 'Profil berhasil disimpan!');
    }
}