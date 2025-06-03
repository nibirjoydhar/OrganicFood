<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('customer.dashboard')" class="text-xl font-bold text-gray-800">
                                Customer Dashboard
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <Link :href="route('customer.products.index')" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Shop
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-6">
                    <!-- Sidebar -->
                    <div class="col-span-12 md:col-span-3">
                        <div class="bg-white shadow rounded-lg">
                            <nav class="space-y-1">
                                <Link :href="route('customer.dashboard')" :class="[route().current('customer.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium']">
                                    <span class="truncate">Dashboard</span>
                                </Link>
                                <Link :href="route('customer.dashboard.orders')" :class="[route().current('customer.dashboard.orders') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium']">
                                    <span class="truncate">Orders</span>
                                </Link>
                                <Link :href="route('customer.wishlist.index')" :class="[route().current('customer.wishlist.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium']">
                                    <span class="truncate">Wishlist</span>
                                </Link>
                                <Link :href="route('customer.dashboard.reviews')" :class="[route().current('customer.dashboard.reviews') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium']">
                                    <span class="truncate">Reviews</span>
                                </Link>
                                <Link :href="route('customer.dashboard.addresses')" :class="[route().current('customer.dashboard.addresses') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium']">
                                    <span class="truncate">Addresses</span>
                                </Link>
                                <Link :href="route('customer.dashboard.profile')" :class="[route().current('customer.dashboard.profile') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-3 py-2 text-sm font-medium']">
                                    <span class="truncate">Profile</span>
                                </Link>
                            </nav>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="col-span-12 md:col-span-9">
                        <div class="bg-white shadow rounded-lg">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script> 