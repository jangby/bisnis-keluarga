<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = ['id'];

    // Relasi: Item milik satu keranjang
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relasi: Item adalah satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper: Hitung subtotal item ini (Harga x Jumlah)
    public function getSubtotalAttribute()
    {
        // Prioritaskan harga saat masuk keranjang (price_at_add)
        return $this->price_at_add * $this->quantity;
    }
}