<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    protected $guarded = ['id']; // Izinkan isi data massal

    // Relasi ke Produk Utama (Barang Jadi)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi ke Bahan Baku
    public function material()
    {
        // Ini merujuk ke tabel products juga, tapi sebagai bahan
        return $this->belongsTo(Product::class, 'material_id');
    }
}