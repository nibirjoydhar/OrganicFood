<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'payment'])
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'payment', 'timeline', 'user']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order,
            'availableStatuses' => $this->getAvailableStatuses($order),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $order->updateStatus($request->status, $request->comment);
            return back()->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function getAvailableStatuses(Order $order)
    {
        $allStatuses = [
            Order::STATUS_PENDING => 'Pending',
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_SHIPPED => 'Shipped',
            Order::STATUS_DELIVERED => 'Delivered',
            Order::STATUS_CANCELLED => 'Cancelled',
        ];

        $availableStatuses = [];
        foreach ($allStatuses as $status => $label) {
            if ($order->canUpdateStatus($status)) {
                $availableStatuses[$status] = $label;
            }
        }

        return $availableStatuses;
    }
} 