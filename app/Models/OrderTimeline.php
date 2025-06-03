<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
} 