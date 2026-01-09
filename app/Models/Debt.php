<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Debt extends Model
{
    // Izinkan Mass Assignment untuk semua kolom
    protected $guarded = ['id'];

    // Casting tipe data agar otomatis dikonversi oleh Laravel
    protected $casts = [
        'due_date' => 'date', // Otomatis jadi objek Carbon saat dipanggil
        'amount' => 'decimal:2',
    ];

    // --- RELASI ---

    // Relasi ke User (Siapa yang mencatat utang ini?)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kontak (Siapa yang punya utang/piutang?)
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    // --- HELPER ATTRIBUTES ---

    // Cek apakah sudah lunas
    public function getIsPaidAttribute()
    {
        return $this->status === 'paid';
    }

    // Cek apakah sudah jatuh tempo (Telat Bayar)
    public function getIsOverdueAttribute()
    {
        // Jika belum lunas DAN tanggal jatuh tempo sudah lewat hari ini
        return $this->status === 'unpaid' && $this->due_date->isPast();
    }

    // Hitung sisa hari sebelum jatuh tempo
    public function getDaysUntilDueAttribute()
    {
        if ($this->status === 'paid') return 0;
        
        return Carbon::now()->diffInDays($this->due_date, false); 
        // false = hasil bisa negatif jika sudah lewat
    }

    // Tambahkan relasi ini
public function employee()
{
    return $this->belongsTo(User::class, 'employee_id');
}
}