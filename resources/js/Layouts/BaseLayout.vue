<template>
    <div>
        <Head>
            <!-- Basic Meta Tags -->
            <title>{{ title || 'Organic Food' }}</title>
            <meta name="description" :content="description">
            <meta name="keywords" :content="keywords">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="robots" :content="robots">
            <link rel="canonical" :href="canonical">

            <!-- Open Graph Tags -->
            <meta property="og:title" :content="title">
            <meta property="og:description" :content="description">
            <meta property="og:image" :content="image">
            <meta property="og:url" :content="canonical">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="Organic Food">

            <!-- Twitter Card Tags -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" :content="title">
            <meta name="twitter:description" :content="description">
            <meta name="twitter:image" :content="image">

            <!-- Favicon -->
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="manifest" href="/site.webmanifest">
        </Head>

        <div class="min-h-screen bg-gray-100">
            <!-- Top Bar -->
            <div class="bg-green-600">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-10 items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <a href="tel:+1234567890" class="text-sm text-white hover:text-gray-200">
                                <span class="hidden sm:inline">Call us:</span> +1 (234) 567-890
                            </a>
                            <a href="mailto:support@organicfood.com" class="text-sm text-white hover:text-gray-200">
                                <span class="hidden sm:inline">Email:</span> support@organicfood.com
                            </a>
                        </div>
                        <div class="flex items-center space-x-6">
                            <a href="#" class="text-sm text-white hover:text-gray-200">Track Order</a>
                            <a href="#" class="text-sm text-white hover:text-gray-200">Store Locator</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="bg-white border-b border-gray-100">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('home')" class="text-xl font-bold text-green-600">
                                    Organic Food
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <NavLink :href="route('home')" :active="route().current('home')">
                                    Home
                                </NavLink>
                                
                                <!-- Categories Dropdown -->
                                <div class="relative">
                                    <button
                                        @mouseenter="showingCategoryDropdown = true"
                                        @mouseleave="showingCategoryDropdown = false"
                                        class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out"
                                    >
                                        Categories
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div
                                        v-show="showingCategoryDropdown"
                                        @mouseenter="showingCategoryDropdown = true"
                                        @mouseleave="showingCategoryDropdown = false"
                                        class="absolute z-50 mt-2 w-48 rounded-md shadow-lg py-1 bg-white"
                                    >
                                        <Link
                                            :href="route('categories.show', 'fruits')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            Fruits
                                        </Link>
                                        <Link
                                            :href="route('categories.show', 'vegetables')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            Vegetables
                                        </Link>
                                        <Link
                                            :href="route('categories.show', 'dairy')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >
                                            Dairy Products
                                        </Link>
                                        <Link
                                            :href="route('categories.index')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t"
                                        >
                                            View All Categories
                                        </Link>
                                    </div>
                                </div>

                                <NavLink :href="route('products.index')" :active="route().current('products.index')">
                                    Shop
                                </NavLink>
                                <NavLink :href="route('about')" :active="route().current('about')">
                                    About
                                </NavLink>
                                <NavLink :href="route('contact')" :active="route().current('contact')">
                                    Contact
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                            <!-- Search -->
                            <div class="relative">
                                <input
                                    type="text"
                                    placeholder="Search products..."
                                    class="w-64 rounded-full border-gray-300 pl-10 pr-4 focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Cart -->
                            <Link :href="route('cart')" class="relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-green-600 rounded-full">2</span>
                            </Link>

                            <!-- Wishlist -->
                            <Link :href="route('wishlist')" class="relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </Link>

                            <!-- User Menu -->
                            <div v-if="$page.props.auth.user" class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('customer.dashboard')">
                                            Dashboard
                                        </DropdownLink>
                                        <DropdownLink :href="route('customer.orders')">
                                            My Orders
                                        </DropdownLink>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="text-sm text-gray-500 hover:text-gray-700"
                                >
                                    Log in
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="ml-4 text-sm text-gray-500 hover:text-gray-700"
                                >
                                    Register
                                </Link>
                            </template>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('home')" :active="route().current('home')">
                            Home
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('categories.index')" :active="route().current('categories.*')">
                            Categories
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('products.index')" :active="route().current('products.index')">
                            Shop
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('about')" :active="route().current('about')">
                            About
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('contact')" :active="route().current('contact')">
                            Contact
                        </ResponsiveNavLink>
                    </div>

                    <!-- Mobile User Menu -->
                    <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('customer.dashboard')">
                                Dashboard
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('customer.orders')">
                                My Orders
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div v-else class="pt-4 pb-1 border-t border-gray-200">
                        <div class="space-y-1">
                            <ResponsiveNavLink :href="route('login')">
                                Log in
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('register')">
                                Register
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200">
                <div class="mx-auto max-w-7xl py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Company Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Company</h3>
                            <ul class="mt-4 space-y-4">
                                <li>
                                    <Link :href="route('about')" class="text-base text-gray-500 hover:text-gray-900">
                                        About Us
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('contact')" class="text-base text-gray-500 hover:text-gray-900">
                                        Contact
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Products -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Products</h3>
                            <ul class="mt-4 space-y-4">
                                <li>
                                    <Link :href="route('products.index')" class="text-base text-gray-500 hover:text-gray-900">
                                        All Products
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('categories.index')" class="text-base text-gray-500 hover:text-gray-900">
                                        Categories
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Customer Service -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Customer Service</h3>
                            <ul class="mt-4 space-y-4">
                                <li>
                                    <Link :href="route('faq')" class="text-base text-gray-500 hover:text-gray-900">
                                        FAQ
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('shipping')" class="text-base text-gray-500 hover:text-gray-900">
                                        Shipping Information
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Newsletter -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Subscribe to our newsletter</h3>
                            <p class="mt-4 text-base text-gray-500">Get the latest updates, deals, and organic food tips.</p>
                            <form class="mt-4 sm:flex sm:max-w-md">
                                <label for="email-address" class="sr-only">Email address</label>
                                <input type="email" name="email-address" id="email-address" autocomplete="email" required class="appearance-none min-w-0 w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:placeholder-gray-400" placeholder="Enter your email" />
                                <div class="mt-3 rounded-md sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                                    <button type="submit" class="w-full bg-green-600 flex items-center justify-center border border-transparent rounded-md py-2 px-4 text-base font-medium text-white hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Subscribe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <p class="text-base text-gray-400 text-center">
                            &copy; {{ new Date().getFullYear() }} Organic Food. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Toast Notifications -->
        <Toast ref="toast" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Toast from '@/Components/Toast.vue';

const showingNavigationDropdown = ref(false);
const showingCategoryDropdown = ref(false);

const props = defineProps({
    title: String,
    description: String,
    keywords: String,
    robots: String,
    canonical: String,
    image: String,
});

const page = usePage();
</script> 