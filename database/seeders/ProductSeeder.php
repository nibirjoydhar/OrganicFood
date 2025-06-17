<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $products = $this->getProductsForCategory($category->name);
            foreach ($products as $product) {
                Product::create([
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'] ?? null,
                    'stock' => rand(10, 100),
                    'category_id' => $category->id,
                    'image' => $product['image'],
                    'is_featured' => $product['is_featured'] ?? false
                ]);
            }
        }
    }

    private function getProductsForCategory($categoryName): array
    {
        return match ($categoryName) {
            'Fresh Vegetables' => [
                [
                    'name' => 'Organic Carrots',
                    'description' => 'Fresh organic carrots, perfect for salads and cooking',
                    'price' => 2.99,
                    'image' => 'images/products/carrots.jpg',
                    'is_featured' => true
                ],
                [
                    'name' => 'Fresh Spinach',
                    'description' => 'Organic baby spinach leaves',
                    'price' => 3.99,
                    'image' => 'images/products/spinach.jpg'
                ],
                // Add more vegetables...
            ],
            'Fresh Fruits' => [
                [
                    'name' => 'Organic Apples',
                    'description' => 'Sweet and crispy organic apples',
                    'price' => 4.99,
                    'sale_price' => 3.99,
                    'image' => 'images/products/apples.jpg',
                    'is_featured' => true
                ],
                [
                    'name' => 'Fresh Bananas',
                    'description' => 'Ripe and ready to eat bananas',
                    'price' => 2.99,
                    'image' => 'images/products/bananas.jpg'
                ],
                // Add more fruits...
            ],
            'Dairy & Eggs' => [
                [
                    'name' => 'Organic Milk',
                    'description' => 'Fresh organic whole milk',
                    'price' => 5.99,
                    'image' => 'images/products/milk.jpg',
                    'is_featured' => true
                ],
                [
                    'name' => 'Free Range Eggs',
                    'description' => 'Farm fresh free-range eggs',
                    'price' => 6.99,
                    'image' => 'images/products/eggs.jpg'
                ],
                // Add more dairy products...
            ],
            default => []
        };
    }
} 