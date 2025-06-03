<template>
  <div class="space-y-6">
    <!-- Review Stats -->
    <div class="flex items-center gap-4">
      <div class="flex items-center">
        <div class="text-3xl font-bold">{{ product.average_rating.toFixed(1) }}</div>
        <div class="ml-2">
          <div class="flex items-center">
            <StarRating :rating="product.average_rating" :readonly="true" />
          </div>
          <div class="text-sm text-gray-500">
            {{ product.review_count }} {{ product.review_count === 1 ? 'review' : 'reviews' }}
          </div>
        </div>
      </div>
    </div>

    <!-- Review Form -->
    <div v-if="canReview" class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
      <form @submit.prevent="submitReview" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Rating
          </label>
          <StarRating v-model="form.rating" :readonly="false" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Comment (optional)
          </label>
          <textarea
            v-model="form.comment"
            rows="4"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
            placeholder="Share your experience with this product..."
          ></textarea>
        </div>
        <div>
          <button
            type="submit"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50"
            :disabled="processing || !form.rating"
          >
            <span v-if="processing">Submitting...</span>
            <span v-else>Submit Review</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6">
      <h3 class="text-lg font-semibold">Customer Reviews</h3>
      <div v-if="product.reviews.length === 0" class="text-gray-500">
        No reviews yet.
      </div>
      <div v-else class="space-y-6">
        <div
          v-for="review in product.reviews"
          :key="review.id"
          class="bg-white rounded-lg shadow p-6"
        >
          <div class="flex justify-between items-start">
            <div>
              <div class="flex items-center gap-2">
                <StarRating :rating="review.rating" :readonly="true" />
                <span class="text-sm text-gray-500">
                  {{ new Date(review.created_at).toLocaleDateString() }}
                </span>
              </div>
              <div class="font-medium mt-1">{{ review.user.name }}</div>
              <div class="text-gray-600 mt-2">{{ review.comment }}</div>
            </div>
            <button
              v-if="canDeleteReview(review)"
              @click="deleteReview(review)"
              class="text-red-600 hover:text-red-700"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import StarRating from './StarRating.vue'

const props = defineProps({
  product: Object,
  canReview: Boolean,
  orderId: Number,
})

const processing = ref(false)
const form = ref({
  rating: 0,
  comment: '',
  product_id: props.product.id,
  order_id: props.orderId,
})

const submitReview = async () => {
  processing.value = true
  try {
    await router.post(route('customer.reviews.store'), form.value)
    form.value = {
      rating: 0,
      comment: '',
      product_id: props.product.id,
      order_id: props.orderId,
    }
  } finally {
    processing.value = false
  }
}

const deleteReview = async (review) => {
  if (confirm('Are you sure you want to delete this review?')) {
    await router.delete(route('customer.reviews.destroy', review.id))
  }
}

const canDeleteReview = (review) => {
  const user = usePage().props.auth.user
  return user.isAdmin || user.id === review.user_id
}
</script> 