<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'clock_in', 'clock_out',
        'lat_in', 'long_in', 'lat_out', 'long_out',
        'status', 'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}