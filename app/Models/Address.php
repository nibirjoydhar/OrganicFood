<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function setDefault($userId, $addressId)
    {
        static::where('user_id', $userId)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        static::where('user_id', $userId)
            ->where('id', $addressId)
            ->update(['is_default' => true]);
    }
} 