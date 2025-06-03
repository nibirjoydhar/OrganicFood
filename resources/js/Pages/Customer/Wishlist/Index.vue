<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">My Wishlist</h1>

      <!-- Empty State -->
      <div v-if="wishlist.length === 0" class="text-center py-12">
        <svg
          class="mx-auto h-12 w-12 text-gray-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
          />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No items in wishlist</h3>
        <p class="mt-1 text-sm text-gray-500">Start adding some products to your wishlist!</p>
        <div class="mt-6">
          <Link
            :href="route('customer.products.index')"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700"
          >
            Browse Products
          </Link>
        </div>
      </div>

      <!-- Wishlist Grid -->
      <div
        v-else
        class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8"
      >
        <div
          v-for="item in wishlist"
          :key="item.id"
          class="group relative bg-white rounded-lg shadow p-4"
        >
          <!-- Product Image -->
          <div class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden">
            <img
              :src="item.product.images[0]"
              :alt="item.product.name"
              class="w-full h-full object-center object-cover group-hover:opacity-75"
            />
          </div>

          <!-- Product Info -->
          <div class="mt-4">
            <h3 class="text-lg font-medium text-gray-900">
              <Link :href="route('customer.products.show', item.product.id)">
                {{ item.product.name }}
              </Link>
            </h3>
            <div class="mt-1 flex items-center">
              <StarRating :rating="item.product.average_rating" :readonly="true" />
              <span class="ml-2 text-sm text-gray-500">
                ({{ item.product.review_count }})
              </span>
            </div>
            <p class="mt-2 text-lg font-medium text-gray-900">${{ item.product.price }}</p>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex justify-between items-center">
            <button
              type="button"
              class="text-red-600 hover:text-red-700 text-sm font-medium"
              @click="removeFromWishlist(item.product.id)"
            >
              Remove
            </button>
            <Link
              :href="route('customer.products.show', item.product.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
            >
              View Details
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import StarRating from '@/Components/Reviews/StarRating.vue'

const props = defineProps({
  wishlist: Array,
})

const removeFromWishlist = async (productId) => {
  try {
    await axios.post(route('customer.wishlist.toggle'), {
      product_id: productId,
    })
    window.location.reload()
  } catch (error) {
    console.error('Error removing from wishlist:', error)
  }
}
</script> 