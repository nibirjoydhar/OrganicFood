<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    featuredProducts: {
        type: Array,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    newArrivals: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Home</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Featured Products -->
                <div class="mb-12">
                    <h2 class="mb-6 text-2xl font-bold">Featured Products</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        <div v-for="product in featuredProducts" :key="product.id" 
                            class="overflow-hidden rounded-lg bg-white shadow-lg transition-transform hover:scale-105">
                            <Link :href="route('products.show', product)">
                                <img :src="product.image" :alt="product.name" class="h-48 w-full object-cover">
                                <div class="p-4">
                                    <h3 class="mb-2 text-lg font-semibold">{{ product.name }}</h3>
                                    <p class="text-gray-600">${{ product.price }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="mb-12">
                    <h2 class="mb-6 text-2xl font-bold">Shop by Category</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                        <Link v-for="category in categories" :key="category.id" 
                            :href="route('categories.show', category)"
                            class="group relative overflow-hidden rounded-lg">
                            <img :src="category.image" :alt="category.name" 
                                class="h-64 w-full object-cover transition-transform group-hover:scale-110">
                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40">
                                <h3 class="text-2xl font-bold text-white">{{ category.name }}</h3>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- New Arrivals -->
                <div>
                    <h2 class="mb-6 text-2xl font-bold">New Arrivals</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        <div v-for="product in newArrivals" :key="product.id" 
                            class="overflow-hidden rounded-lg bg-white shadow-lg transition-transform hover:scale-105">
                            <Link :href="route('products.show', product)">
                                <img :src="product.image" :alt="product.name" class="h-48 w-full object-cover">
                                <div class="p-4">
                                    <h3 class="mb-2 text-lg font-semibold">{{ product.name }}</h3>
                                    <p class="text-gray-600">${{ product.price }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 