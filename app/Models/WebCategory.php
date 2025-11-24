<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebCategory extends Model
{
    protected $guarded = ['id'];

    // Relasi: Satu Kategori punya banyak Produk
    public function products()
    {
        return $this->belongsToMany(Product::class, 'web_category_product')
                    ->withTimestamps();
    }
}