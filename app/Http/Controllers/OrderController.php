<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'delivery_option' => 'required|string|in:standard,express',
            'items' => 'required|array|min:1',
            'subtotal' => 'required|numeric|min:0',
            'shipping' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'address_2' => $request->address_2,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'delivery_method' => $request->delivery_option,
                'subtotal' => $request->subtotal,
                'shipping' => $request->shipping,
                'discount' => $request->discount,
                'total' => $request->total,
            ]);

            // Create order items and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Update stock
                $product->decrement('stock', $item['quantity']);
            }

            // Store coupon information if used
            if ($request->coupon) {
                $order->update([
                    'coupon_code' => $request->coupon['code'],
                    'coupon_type' => $request->coupon['type'],
                    'coupon_value' => $request->coupon['value'],
                ]);
            }

            // Clear cart and coupon from session
            session()->forget(['cart', 'coupon']);

            DB::commit();

            // Redirect to payment
            return redirect()->route('customer.payment.initiate', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'payment']);

        return Inertia::render('Customer/Orders/Show', [
            'order' => $order,
        ]);
    }
} 