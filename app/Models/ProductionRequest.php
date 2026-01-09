<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionRequest extends Model
{
    // Agar bisa diisi massal
    protected $guarded = ['id'];

    // Agar kolom 'items' otomatis dibaca sebagai Array/JSON
    protected $casts = [
        'items' => 'array',
        'requested_at' => 'datetime',
    ];

    // Relasi ke User (Siapa yang request)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}