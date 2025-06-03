<template>
    <CustomerDashboardLayout>
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-900">Order History</h1>

            <div class="mt-6">
                <div class="overflow-x-auto">
                    <table v-if="orders.data.length" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="order in orders.data" :key="order.id">
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
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="flex flex-col space-y-1">
                                        <div v-for="item in order.items" :key="item.id" class="text-sm">
                                            {{ item.product.name }} ({{ item.quantity }})
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <Link :href="route('customer.orders.show', order.id)" class="text-indigo-600 hover:text-indigo-900">View Details</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="text-center py-4 text-gray-500">No orders found.</div>
                </div>

                <!-- Pagination -->
                <div v-if="orders.data.length" class="mt-6">
                    <Pagination :links="orders.links" />
                </div>
            </div>
        </div>
    </CustomerDashboardLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerDashboardLayout from '@/Layouts/CustomerDashboardLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    orders: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script> 