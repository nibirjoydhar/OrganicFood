<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['seller'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($request->price_range, function ($query, $priceRange) {
                [$min, $max] = explode('-', $priceRange);
                if ($max === '+') {
                    $query->where('price', '>=', $min);
                } else {
                    $query->whereBetween('price', [$min, $max]);
                }
            })
            ->when($request->sort, function ($query, $sort) {
                switch ($sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    default:
                        $query->latest();
                        break;
                }
            }, function ($query) {
                $query->latest();
            });

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return Inertia::render('Customer/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'price_range', 'sort']),
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['seller', 'reviews.user', 'images']);
        $canReview = auth()->check() && !$product->reviews()->where('user_id', auth()->id())->exists();
        $isWishlisted = auth()->check() && $product->wishlists()->where('user_id', auth()->id())->exists();

        return Inertia::render('Customer/Products/Show', [
            'product' => $product,
            'canReview' => $canReview,
            'isWishlisted' => $isWishlisted,
        ]);
    }
} 