<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Customer/Dashboard/Index', [
            'recentOrders' => $recentOrders,
        ]);
    }

    public function orders()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Dashboard/Orders', [
            'orders' => $orders,
        ]);
    }

    public function reviews()
    {
        $reviews = auth()->user()->reviews()
            ->with('product')
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Dashboard/Reviews', [
            'reviews' => $reviews,
        ]);
    }

    public function addresses()
    {
        $addresses = auth()->user()->addresses()
            ->latest()
            ->get();

        return Inertia::render('Customer/Dashboard/Addresses', [
            'addresses' => $addresses,
        ]);
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $address = auth()->user()->addresses()->create($validated);

        if ($request->is_default) {
            Address::setDefault(auth()->id(), $address->id);
        }

        return back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $address->update($validated);

        if ($request->is_default) {
            Address::setDefault(auth()->id(), $address->id);
        }

        return back()->with('success', 'Address updated successfully.');
    }

    public function deleteAddress(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return back()->with('success', 'Address deleted successfully.');
    }

    public function profile()
    {
        return Inertia::render('Customer/Dashboard/Profile', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }

            $validated['password'] = Hash::make($request->new_password);
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
} 