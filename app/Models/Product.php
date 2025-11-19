<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    // Produk ini milik divisi apa?
    public function product_line()
    {
        return $this->belongsTo(ProductLine::class);
    }

    // Cek riwayat stok produk ini
    public function inventory_logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Ambil daftar resep (Bahan apa saja yang dibutuhkan produk ini)
    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'product_id');
    }
}
