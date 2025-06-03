<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        // Redirect to cart if empty
        if (!session()->has('cart') || empty(session()->get('cart'))) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Pre-fill form with user data if available
        $user = auth()->user();
        $defaultData = [];
        
        if ($user) {
            $defaultData = [
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'address' => $user->address,
                'address_2' => $user->address_2,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
            ];
        }

        return Inertia::render('Customer/Checkout/Index', [
            'defaultData' => $defaultData,
        ]);
    }
} 