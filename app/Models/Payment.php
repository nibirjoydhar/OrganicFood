<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'status',
        'payment_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
} 