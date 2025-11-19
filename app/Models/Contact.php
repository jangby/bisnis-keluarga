<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    // Izinkan pengisian massal untuk semua kolom kecuali ID
    protected $guarded = ['id'];

    /**
     * Relasi ke Transaksi Keuangan
     * Satu kontak bisa memiliki banyak riwayat transaksi (beli bahan/jual produk)
     */
    public function finance_records()
    {
        return $this->hasMany(FinanceRecord::class);
    }

    /**
     * Relasi ke Hutang Piutang
     * Satu kontak bisa memiliki banyak catatan hutang/piutang
     */
    public function debts()
    {
        return $this->hasMany(Debt::class);
    }
}