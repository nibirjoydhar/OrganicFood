<template>
    <CustomerDashboardLayout>
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            
            <!-- Recent Orders -->
            <div class="mt-6">
                <h2 class="text-lg font-medium text-gray-900">Recent Orders</h2>
                <div class="mt-4 overflow-x-auto">
                    <table v-if="recentOrders.length" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="order in recentOrders" :key="order.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ order.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(order.created_at) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        {
                                            'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                            'bg-blue-100 text-blue-800': order.status === 'processing',
                                            'bg-green-100 text-green-800': order.status === 'delivered',
                                            'bg-red-100 text-red-800': order.status === 'cancelled'
                                        }
                                    ]">
                                        {{ order.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">৳{{ order.total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <Link :href="route('customer.orders.show', order.id)" class="text-indigo-600 hover:text-indigo-900">View Details</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="text-center py-4 text-gray-500">No recent orders found.</div>
                </div>
                <div class="mt-4 text-right">
                    <Link :href="route('customer.dashboard.orders')" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        View all orders
                        <span aria-hidden="true"> &rarr;</span>
                    </Link>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <Link :href="route('customer.wishlist.index')" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true" />
                        <p class="text-sm font-medium text-gray-900">Wishlist</p>
                        <p class="text-sm text-gray-500">View your saved items</p>
                    </div>
                </Link>

                <Link :href="route('customer.dashboard.reviews')" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true" />
                        <p class="text-sm font-medium text-gray-900">Reviews</p>
                        <p class="text-sm text-gray-500">Manage your product reviews</p>
                    </div>
                </Link>

                <Link :href="route('customer.dashboard.addresses')" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true" />
                        <p class="text-sm font-medium text-gray-900">Addresses</p>
                        <p class="text-sm text-gray-500">Manage delivery addresses</p>
                    </div>
                </Link>
            </div>
        </div>
    </CustomerDashboardLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerDashboardLayout from '@/Layouts/CustomerDashboardLayout.vue';

const props = defineProps({
    recentOrders: {
        type: Array,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script> 