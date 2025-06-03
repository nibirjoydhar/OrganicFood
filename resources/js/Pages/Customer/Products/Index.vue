<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Search and Filter Section -->
    <div class="mb-8 space-y-4">
      <div class="flex gap-4">
        <div class="flex-1">
          <input
            v-model="search"
            type="text"
            placeholder="Search products..."
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
            @input="handleSearch"
          />
        </div>
        <div class="flex gap-4">
          <select
            v-model="selectedCategory"
            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
            @change="handleFilters"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <select
            v-model="priceRange"
            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
            @change="handleFilters"
          >
            <option value="">Price Range</option>
            <option value="0-25">$0 - $25</option>
            <option value="25-50">$25 - $50</option>
            <option value="50-100">$50 - $100</option>
            <option value="100+">$100+</option>
          </select>
          <select
            v-model="sortBy"
            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
            @change="handleSort"
          >
            <option value="latest">Latest</option>
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        v-for="product in products.data"
        :key="product.id"
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
      >
        <Link :href="route('customer.products.show', product.id)">
          <img
            :src="product.image"
            :alt="product.name"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ product.name }}</h3>
            <p class="text-gray-600">{{ product.seller.name }}</p>
            <div class="mt-2 flex justify-between items-center">
              <span class="text-green-600 font-bold">${{ product.price }}</span>
              <div class="flex items-center">
                <StarIcon class="h-5 w-5 text-yellow-400" />
                <span class="ml-1">{{ product.rating }}</span>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
      <Pagination :links="products.links" />
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { StarIcon } from '@heroicons/vue/20/solid'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object
})

const search = ref(props.filters?.search || '')
const selectedCategory = ref(props.filters?.category || '')
const priceRange = ref(props.filters?.price_range || '')
const sortBy = ref(props.filters?.sort || 'latest')

const handleSearch = () => {
  router.get(
    route('customer.products.index'),
    {
      search: search.value,
      category: selectedCategory.value,
      price_range: priceRange.value,
      sort: sortBy.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  )
}

const handleFilters = () => {
  router.get(
    route('customer.products.index'),
    {
      search: search.value,
      category: selectedCategory.value,
      price_range: priceRange.value,
      sort: sortBy.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  )
}

const handleSort = () => {
  router.get(
    route('customer.products.index'),
    {
      search: search.value,
      category: selectedCategory.value,
      price_range: priceRange.value,
      sort: sortBy.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  )
}
</script> 