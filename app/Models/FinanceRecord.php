<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceRecord extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'transaction_date' => 'date',
        'due_date' => 'date',
    ];

    public function product_line()
    {
        return $this->belongsTo(ProductLine::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function contact() {
        return $this->belongsTo(Contact::class);
    }
}