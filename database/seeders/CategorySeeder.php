<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fresh Vegetables',
                'description' => 'Fresh and organic vegetables sourced directly from local farmers',
                'image' => 'images/categories/vegetables.jpg'
            ],
            [
                'name' => 'Fresh Fruits',
                'description' => 'Seasonal and exotic fruits from around the world',
                'image' => 'images/categories/fruits.jpg'
            ],
            [
                'name' => 'Dairy & Eggs',
                'description' => 'Fresh dairy products and free-range eggs',
                'image' => 'images/categories/dairy.jpg'
            ],
            [
                'name' => 'Meat & Fish',
                'description' => 'Fresh meat and seafood from trusted suppliers',
                'image' => 'images/categories/meat.jpg'
            ],
            [
                'name' => 'Bakery',
                'description' => 'Fresh bread and baked goods made daily',
                'image' => 'images/categories/bakery.jpg'
            ],
            [
                'name' => 'Organic Groceries',
                'description' => 'Organic pantry essentials and snacks',
                'image' => 'images/categories/groceries.jpg'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image']
            ]);
        }
    }
} 