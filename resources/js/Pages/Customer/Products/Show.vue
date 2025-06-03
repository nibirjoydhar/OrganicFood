<template>
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Image Gallery -->
      <div class="space-y-4">
        <div class="relative h-96 bg-gray-100 rounded-lg overflow-hidden">
          <img
            :src="mainImage || product.images[0]"
            :alt="product.name"
            class="w-full h-full object-cover"
          />
        </div>
        <div class="grid grid-cols-4 gap-4">
          <button
            v-for="(image, index) in product.images"
            :key="index"
            @click="mainImage = image"
            class="relative h-24 bg-gray-100 rounded-lg overflow-hidden hover:ring-2 hover:ring-green-500"
            :class="{ 'ring-2 ring-green-500': mainImage === image }"
          >
            <img
              :src="image"
              :alt="'${product.name} image ${index + 1}'"
              class="w-full h-full object-cover"
            />
          </button>
        </div>
      </div>

      <!-- Product Info -->
      <div class="space-y-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ product.name }}</h1>
          <div class="mt-2 flex items-center space-x-4">
            <div class="flex items-center">
              <StarIcon v-for="i in Math.floor(product.rating)" :key="i" class="h-5 w-5 text-yellow-400" />
              <StarIcon
                v-if="product.rating % 1 !== 0"
                class="h-5 w-5 text-yellow-400"
                :class="{ 'opacity-50': true }"
              />
              <span class="ml-2 text-gray-600">({{ product.reviews_count }} reviews)</span>
            </div>
            <span class="text-2xl font-bold text-green-600">${{ product.price }}</span>
          </div>
        </div>

        <div class="border-t border-b py-4">
          <h2 class="text-lg font-semibold mb-2">Description</h2>
          <p class="text-gray-600">{{ product.description }}</p>
        </div>

        <div class="border-b py-4">
          <h2 class="text-lg font-semibold mb-2">Seller Information</h2>
          <div class="flex items-center space-x-4">
            <img
              :src="product.seller.avatar"
              :alt="product.seller.name"
              class="h-12 w-12 rounded-full"
            />
            <div>
              <p class="font-medium">{{ product.seller.name }}</p>
              <p class="text-sm text-gray-600">Member since {{ product.seller.joined_date }}</p>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="flex items-center space-x-4">
            <button
              @click="addToCart"
              class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
            >
              Add to Cart
            </button>
            <button
              @click="toggleWishlist"
              class="p-3 rounded-lg border hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
              :class="{ 'text-red-600': isWishlisted }"
            >
              <HeartIcon class="h-6 w-6" :class="{ 'fill-current': isWishlisted }" />
            </button>
          </div>
          <p class="text-sm text-gray-600">
            {{ product.stock > 0 ? \`${product.stock} items in stock\` : 'Out of stock' }}
          </p>
        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-12">
      <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>
      
      <!-- Add Review Form -->
      <div v-if="canReview" class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
        <div class="space-y-4">
          <div class="flex items-center space-x-2">
            <StarIcon
              v-for="i in 5"
              :key="i"
              @click="newReview.rating = i"
              class="h-6 w-6 cursor-pointer"
              :class="i <= newReview.rating ? 'text-yellow-400' : 'text-gray-300'"
            />
          </div>
          <textarea
            v-model="newReview.comment"
            rows="4"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
            placeholder="Share your thoughts about this product..."
          ></textarea>
          <button
            @click="submitReview"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
          >
            Submit Review
          </button>
        </div>
      </div>

      <!-- Reviews List -->
      <div class="space-y-6">
        <div
          v-for="review in product.reviews"
          :key="review.id"
          class="bg-white p-6 rounded-lg shadow"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
              <img
                :src="review.user.avatar"
                :alt="review.user.name"
                class="h-10 w-10 rounded-full"
              />
              <div>
                <p class="font-medium">{{ review.user.name }}</p>
                <p class="text-sm text-gray-600">{{ review.created_at }}</p>
              </div>
            </div>
            <div class="flex items-center">
              <StarIcon v-for="i in review.rating" :key="i" class="h-5 w-5 text-yellow-400" />
            </div>
          </div>
          <p class="text-gray-600">{{ review.comment }}</p>
        </div>
      </div>

      <!-- Pagination for reviews -->
      <div v-if="product.reviews.length" class="mt-8">
        <Pagination :links="product.reviews_links" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { StarIcon, HeartIcon } from '@heroicons/vue/24/outline'
import Pagination from '@/Components/Pagination.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  product: Object,
  canReview: Boolean,
  isWishlisted: Boolean,
})

const mainImage = ref(props.product.images[0])

const newReview = ref({
  rating: 0,
  comment: '',
})

const addToCart = () => {
  router.post(route('customer.cart.add'), {
    product_id: props.product.id,
    quantity: 1,
  })
}

const toggleWishlist = () => {
  if (props.isWishlisted) {
    router.delete(route('customer.wishlist.remove', props.product.id))
  } else {
    router.post(route('customer.wishlist.add'), {
      product_id: props.product.id,
    })
  }
}

const submitReview = () => {
  router.post(route('customer.reviews.store'), {
    product_id: props.product.id,
    rating: newReview.value.rating,
    comment: newReview.value.comment,
  })
}
</script> 