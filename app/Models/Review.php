<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function canReview($userId, $productId, $orderId)
    {
        // Check if user has already reviewed this product for this order
        $exists = static::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->exists();

        if ($exists) {
            return false;
        }

        // Check if order exists and is delivered
        $order = Order::find($orderId);
        if (!$order || $order->status !== Order::STATUS_DELIVERED) {
            return false;
        }

        // Check if product was in the order
        $orderHasProduct = $order->items()
            ->where('product_id', $productId)
            ->exists();

        return $orderHasProduct;
    }
} 