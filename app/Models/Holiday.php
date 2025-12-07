<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['date', 'description'];
    
    // Casting agar otomatis jadi object Carbon
    protected $casts = [
        'date' => 'date', 
    ];
}