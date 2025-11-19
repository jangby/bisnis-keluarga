<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $guarded = ['id'];

    public function finance_records()
    {
        return $this->hasMany(FinanceRecord::class);
    }
}