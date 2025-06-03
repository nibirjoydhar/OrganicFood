<?php

namespace App\Models;

use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_FAILED = 'payment_failed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'payment_id',
        'order_number',
        'status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'address_2',
        'city',
        'postal_code',
        'delivery_method',
        'subtotal',
        'shipping',
        'discount',
        'total',
        'coupon_code',
        'coupon_type',
        'coupon_value',
        'payment_status',
        'payment_method',
        'shipping_address',
        'billing_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'coupon_value' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($order) {
            $order->user->notify(new OrderPlaced($order));
        });

        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $order->previousStatus = $order->getOriginal('status');
            }
        });

        static::updated(function ($order) {
            if ($order->wasChanged('status')) {
                $order->user->notify(new OrderStatusUpdated(
                    $order,
                    $order->previousStatus
                ));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function timeline()
    {
        return $this->hasMany(OrderTimeline::class)->orderBy('created_at', 'desc');
    }

    public function canUpdateStatus($newStatus)
    {
        $allowedTransitions = [
            self::STATUS_PENDING => [self::STATUS_PROCESSING, self::STATUS_CANCELLED],
            self::STATUS_PAYMENT_PENDING => [self::STATUS_PROCESSING, self::STATUS_PAYMENT_FAILED, self::STATUS_CANCELLED],
            self::STATUS_PROCESSING => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_DELIVERED],
            self::STATUS_DELIVERED => [],
            self::STATUS_CANCELLED => [],
            self::STATUS_PAYMENT_FAILED => [self::STATUS_PAYMENT_PENDING, self::STATUS_CANCELLED],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    public function updateStatus($newStatus, $comment = null)
    {
        if (!$this->canUpdateStatus($newStatus)) {
            throw new \Exception("Cannot transition from {$this->status} to {$newStatus}");
        }

        $this->status = $newStatus;
        $this->save();

        // Create timeline entry
        $this->timeline()->create([
            'status' => $newStatus,
            'comment' => $comment,
        ]);

        return true;
    }
}
