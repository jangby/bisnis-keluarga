<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // [1] Import Ini

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; // [2] Tambahkan SoftDeletes di sini

    protected $fillable = [
    'name',
    'email',
    'password',
    'role', // <--- Pastikan ini ada!
    'phone', 
    'address',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function leaveRequests()
{
    return $this->hasMany(LeaveRequest::class);
}
}