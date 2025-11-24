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

    // [BARU] Relasi ke Kategori Web
    public function web_categories()
    {
        return $this->belongsToMany(WebCategory::class, 'web_category_product');
    }

    // [BARU] Helper untuk cek apakah produk sedang diskon
    public function getHasDiscountAttribute()
    {
        // Diskon aktif jika discount_price diisi DAN lebih kecil dari harga jual asli
        return $this->discount_price > 0 && $this->discount_price < $this->sell_price;
    }

    // [BARU] Ambil harga final (Otomatis pilih harga diskon kalau ada)
    public function getFinalPriceAttribute()
    {
        return $this->has_discount ? $this->discount_price : $this->sell_price;
    }
}
