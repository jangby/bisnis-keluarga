<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id'];

    // Relasi: Keranjang punya banyak item
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relasi: Keranjang milik user (opsional, bisa guest)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}