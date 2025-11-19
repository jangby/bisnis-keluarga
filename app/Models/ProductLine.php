<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    protected $guarded = ['id']; // Izinkan semua kolom diisi kecuali ID

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function finance_records()
    {
        return $this->hasMany(FinanceRecord::class);
    }
}