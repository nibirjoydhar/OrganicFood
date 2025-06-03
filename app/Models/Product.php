<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'images',
        'average_rating',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
        'average_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function updateReviewStats()
    {
        $stats = $this->reviews()
            ->selectRaw('AVG(rating) as average_rating, COUNT(*) as review_count')
            ->first();

        $this->update([
            'average_rating' => $stats->average_rating ?? 0,
            'review_count' => $stats->review_count ?? 0,
        ]);
    }
}
