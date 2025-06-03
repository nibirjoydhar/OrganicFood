<script setup>
import { Head, Link } from '@inertiajs/vue3';
import BaseLayout from '@/Layouts/BaseLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head>
        <title>Organic Food - Fresh & Healthy Products</title>
        <meta name="description" content="Shop fresh, organic, and healthy food products at Organic Food. We offer a wide selection of natural and sustainable products." />
    </Head>

    <BaseLayout>
        <!-- Hero Section -->
        <div class="relative bg-white">
            <div class="mx-auto max-w-7xl">
                <div class="relative z-10 pt-14 lg:w-full lg:max-w-2xl">
                    <div class="relative px-6 py-32 sm:py-40 lg:px-8 lg:py-56">
                        <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                                Fresh Organic Food for a Healthy Life
                            </h1>
                            <p class="mt-6 text-lg leading-8 text-gray-600">
                                Discover our wide selection of organic products, fresh produce, and healthy alternatives. 
                                Shop with confidence knowing that all our products are carefully selected for quality and sustainability.
                            </p>
                            <div class="mt-10 flex items-center gap-x-6">
                                <Link
                                    :href="route('products.index')"
                                    class="rounded-md bg-green-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                                >
                                    Shop Now
                                </Link>
                                <Link href="#featured" class="text-sm font-semibold leading-6 text-gray-900">
                                    View Featured Products <span aria-hidden="true">→</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img
                    class="aspect-[3/2] object-cover lg:aspect-auto lg:h-full lg:w-full"
                    src="/images/hero-image.jpg"
                    alt="Fresh organic vegetables"
                />
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:max-w-none">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Shop by Category</h2>
                    <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-6 lg:space-y-0">
                        <div v-for="category in categories" :key="category.id" class="group relative">
                            <div class="relative h-80 w-full overflow-hidden rounded-lg bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
                                <img :src="category.image" :alt="category.name" class="h-full w-full object-cover object-center" />
                            </div>
                            <h3 class="mt-6 text-sm text-gray-500">
                                <Link :href="route('categories.show', category.id)">
                                    <span class="absolute inset-0"></span>
                                    {{ category.name }}
                                </Link>
                            </h3>
                            <p class="text-base font-semibold text-gray-900">{{ category.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div id="featured" class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Featured Products</h2>
                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <div v-for="product in featuredProducts" :key="product.id" class="group relative">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
                            <img :src="product.image" :alt="product.name" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <Link :href="route('products.show', product.id)">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ product.name }}
                                    </Link>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ product.category }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">${{ product.price }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-green-600">Why Choose Us</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Everything you need for healthy living
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        We're committed to providing you with the highest quality organic products while ensuring sustainable practices and supporting local farmers.
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                        <div class="flex flex-col">
                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                <svg class="h-5 w-5 flex-none text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                100% Organic Products
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                <p class="flex-auto">All our products are certified organic, ensuring you get the best quality without harmful chemicals.</p>
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                <svg class="h-5 w-5 flex-none text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z" />
                                    <path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z" />
                                </svg>
                                Farm to Table
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                <p class="flex-auto">We work directly with local farmers to bring you the freshest produce possible.</p>
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                <svg class="h-5 w-5 flex-none text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                                </svg>
                                Fast Delivery
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                <p class="flex-auto">Same-day delivery available for orders placed before 2 PM in selected areas.</p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </BaseLayout>
</template> 