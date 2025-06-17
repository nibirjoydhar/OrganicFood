<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        $newArrivals = Product::with('category')
            ->latest()
            ->take(12)
            ->get();

        return Inertia::render('Home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'newArrivals' => $newArrivals
        ]);
    }
} 