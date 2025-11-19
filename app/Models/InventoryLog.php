<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    // BARIS INI YANG DIBUTUHKAN:
    // Mengizinkan semua kolom diisi, kecuali kolom ID (yang auto-increment)
    protected $guarded = ['id']; 

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User (Siapa yang input)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}