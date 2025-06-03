<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = auth()->user()->wishlists()
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'price', 'images', 'average_rating', 'review_count');
            }])
            ->latest()
            ->get();

        return Inertia::render('Customer/Wishlist/Index', [
            'wishlist' => $wishlist,
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $isAdded = Wishlist::toggle(auth()->id(), $request->product_id);

        return response()->json([
            'message' => $isAdded ? 'Added to wishlist' : 'Removed from wishlist',
            'isInWishlist' => $isAdded,
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $isInWishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        return response()->json([
            'isInWishlist' => $isInWishlist,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Product added to wishlist successfully.');
    }

    public function remove(Product $product)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Product removed from wishlist successfully.');
    }
} 