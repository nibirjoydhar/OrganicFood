<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CustomerDashboardController;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Public Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/about', function ()        {
    return Inertia::render('About');
})->name('about');
Route::get('/contact', function () {    
    return Inertia::render('Contact');
})->name('contact');
Route::get('/faq', function () {
    return Inertia::render('FAQ');
})->name('faq');
Route::get('/shipping', function () {
    return Inertia::render('Shipping');
})->name('shipping');

// Customer Routes (Requires Authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // Customer Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('customer.dashboard');
    Route::get('/dashboard/orders', [CustomerDashboardController::class, 'orders'])->name('customer.orders');
    Route::get('/dashboard/reviews', [CustomerDashboardController::class, 'reviews'])->name('customer.dashboard.reviews');
    Route::get('/dashboard/addresses', [CustomerDashboardController::class, 'addresses'])->name('customer.dashboard.addresses');
    Route::post('/dashboard/addresses', [CustomerDashboardController::class, 'storeAddress'])->name('customer.dashboard.addresses.store');
    
    // Shopping Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (Requires Admin Role)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Add more admin routes as needed
});

require __DIR__.'/auth.php';
