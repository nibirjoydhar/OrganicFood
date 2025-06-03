<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Shopping Cart</h1>

    <div v-if="cartStore.loading" class="flex justify-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
    </div>

    <div v-else-if="cartStore.items.length === 0" class="text-center py-8">
      <p class="text-gray-600 mb-4">Your cart is empty</p>
      <Link
        :href="route('customer.products.index')"
        class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700"
      >
        Continue Shopping
      </Link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2">
        <div class="space-y-4">
          <div
            v-for="item in cartStore.items"
            :key="item.id"
            class="bg-white rounded-lg shadow p-4 flex items-center gap-4"
          >
            <img
              :src="item.image"
              :alt="item.name"
              class="w-24 h-24 object-cover rounded-lg"
            />
            <div class="flex-1">
              <h3 class="font-semibold text-lg">{{ item.name }}</h3>
              <p class="text-gray-600">${{ item.price }}</p>
              <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center border rounded-lg">
                  <button
                    @click="cartStore.updateQuantity(item.id, item.quantity - 1)"
                    class="px-3 py-1 hover:bg-gray-100"
                    :disabled="item.quantity <= 1"
                  >
                    -
                  </button>
                  <span class="px-3 py-1 border-x">{{ item.quantity }}</span>
                  <button
                    @click="cartStore.updateQuantity(item.id, item.quantity + 1)"
                    class="px-3 py-1 hover:bg-gray-100"
                    :disabled="item.quantity >= item.stock"
                  >
                    +
                  </button>
                </div>
                <button
                  @click="cartStore.removeItem(item.id)"
                  class="text-red-600 hover:text-red-700"
                >
                  Remove
                </button>
              </div>
            </div>
            <div class="text-right">
              <p class="font-semibold">${{ item.price * item.quantity }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
          <h2 class="text-xl font-semibold">Order Summary</h2>
          
          <!-- Coupon -->
          <div v-if="!cartStore.coupon" class="space-y-2">
            <div class="flex gap-2">
              <input
                v-model="couponCode"
                type="text"
                placeholder="Enter coupon code"
                class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
              />
              <button
                @click="cartStore.applyCoupon(couponCode)"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
              >
                Apply
              </button>
            </div>
          </div>
          
          <div v-else class="flex justify-between items-center">
            <div>
              <span class="font-medium">{{ cartStore.coupon.code }}</span>
              <span class="text-sm text-gray-600 ml-2">
                ({{ cartStore.coupon.type === 'percentage' ? cartStore.coupon.value + '%' : '$' + cartStore.coupon.value }} off)
              </span>
            </div>
            <button
              @click="cartStore.removeCoupon"
              class="text-red-600 hover:text-red-700"
            >
              Remove
            </button>
          </div>

          <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>${{ cartStore.subtotal }}</span>
            </div>
            <div v-if="cartStore.discount > 0" class="flex justify-between text-green-600">
              <span>Discount</span>
              <span>-${{ cartStore.discount }}</span>
            </div>
            <div class="flex justify-between font-semibold text-lg">
              <span>Total</span>
              <span>${{ cartStore.total }}</span>
            </div>
          </div>

          <Link
            :href="route('customer.checkout')"
            class="block w-full text-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700"
          >
            Proceed to Checkout
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useCartStore } from '@/Stores/cartStore'

const cartStore = useCartStore()
const couponCode = ref('')

onMounted(() => {
  cartStore.fetchCart()
})
</script> 