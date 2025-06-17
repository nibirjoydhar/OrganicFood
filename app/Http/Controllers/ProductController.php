<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->when(request('category'), function($query) {
                $query->where('category_id', request('category'));
            })
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            })
            ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
} 