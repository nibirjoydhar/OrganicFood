<template>
  <button
    type="button"
    class="relative inline-flex items-center p-2 text-gray-400 hover:text-red-500 transition-colors"
    :class="{ 'text-red-500': isInWishlist }"
    @click="toggleWishlist"
  >
    <span class="sr-only">{{ isInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}</span>
    <svg
      class="w-6 h-6"
      :class="{ 'fill-current': isInWishlist }"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
      />
    </svg>
    <div
      v-if="showFeedback"
      class="absolute -top-2 -right-2 px-2 py-1 text-xs font-medium text-white rounded-full"
      :class="{
        'bg-green-500': isInWishlist,
        'bg-gray-500': !isInWishlist
      }"
    >
      {{ isInWishlist ? '+1' : '-1' }}
    </div>
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  productId: {
    type: Number,
    required: true
  },
  initialIsInWishlist: {
    type: Boolean,
    default: false
  }
})

const isInWishlist = ref(props.initialIsInWishlist)
const showFeedback = ref(false)
let feedbackTimeout = null

onMounted(async () => {
  if (!props.initialIsInWishlist) {
    try {
      const response = await axios.get(route('customer.wishlist.check'), {
        params: { product_id: props.productId }
      })
      isInWishlist.value = response.data.isInWishlist
    } catch (error) {
      console.error('Error checking wishlist status:', error)
    }
  }
})

const toggleWishlist = async () => {
  try {
    const response = await axios.post(route('customer.wishlist.toggle'), {
      product_id: props.productId
    })
    
    isInWishlist.value = response.data.isInWishlist
    showFeedback.value = true

    if (feedbackTimeout) {
      clearTimeout(feedbackTimeout)
    }

    feedbackTimeout = setTimeout(() => {
      showFeedback.value = false
    }, 1500)
  } catch (error) {
    console.error('Error toggling wishlist:', error)
  }
}
</script> 