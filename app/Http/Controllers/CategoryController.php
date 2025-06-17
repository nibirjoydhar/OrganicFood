<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function show(Category $category)
    {
        $category->load('products');
        return Inertia::render('Categories/Show', [
            'category' => $category
        ]);
    }
} 