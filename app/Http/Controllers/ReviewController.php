<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Check if user can review
            if (!Review::canReview(auth()->id(), $request->product_id, $request->order_id)) {
                throw new \Exception('You cannot review this product.');
            }

            // Create review
            $review = Review::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Update product rating
            $product = Product::find($request->product_id);
            $product->updateReviewStats();

            DB::commit();

            return back()->with('success', 'Review submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        try {
            DB::beginTransaction();

            // Only admin or the review owner can delete
            if (!auth()->user()->isAdmin() && auth()->id() !== $review->user_id) {
                throw new \Exception('Unauthorized action.');
            }

            $productId = $review->product_id;
            $review->delete();

            // Update product rating
            $product = Product::find($productId);
            $product->updateReviewStats();

            DB::commit();

            return back()->with('success', 'Review deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
} 