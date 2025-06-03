<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Checkout</h1>

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

    <form v-else @submit.prevent="submitOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Checkout Form -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Phone Number
              </label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
              </label>
              <input
                v-model="form.email"
                type="email"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                required
              />
            </div>
          </div>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Shipping Address</h2>
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  First Name
                </label>
                <input
                  v-model="form.first_name"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Last Name
                </label>
                <input
                  v-model="form.last_name"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                  required
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Address
              </label>
              <input
                v-model="form.address"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Apartment, suite, etc. (optional)
              </label>
              <input
                v-model="form.address_2"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
              />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  City
                </label>
                <input
                  v-model="form.city"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Postal Code
                </label>
                <input
                  v-model="form.postal_code"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                  required
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Delivery Options -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Delivery Method</h2>
          <div class="space-y-4">
            <label
              v-for="option in deliveryOptions"
              :key="option.id"
              class="flex items-center p-4 border rounded-lg cursor-pointer"
              :class="{ 'border-green-500 bg-green-50': form.delivery_option === option.id }"
            >
              <input
                type="radio"
                :value="option.id"
                v-model="form.delivery_option"
                class="text-green-600 focus:ring-green-500"
                required
              />
              <div class="ml-4 flex-1">
                <div class="font-medium">{{ option.name }}</div>
                <div class="text-sm text-gray-600">{{ option.description }}</div>
              </div>
              <div class="font-medium">${{ option.price }}</div>
            </label>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
          <h2 class="text-xl font-semibold">Order Summary</h2>
          
          <div class="space-y-4">
            <div
              v-for="item in cartStore.items"
              :key="item.id"
              class="flex justify-between py-2"
            >
              <div>
                <span class="font-medium">{{ item.name }}</span>
                <span class="text-gray-600 ml-2">x{{ item.quantity }}</span>
              </div>
              <span>${{ item.price * item.quantity }}</span>
            </div>
          </div>

          <div v-if="cartStore.coupon" class="flex justify-between text-green-600">
            <div>
              <span class="font-medium">{{ cartStore.coupon.code }}</span>
              <span class="text-sm ml-2">
                ({{ cartStore.coupon.type === 'percentage' ? cartStore.coupon.value + '%' : '$' + cartStore.coupon.value }} off)
              </span>
            </div>
            <span>-${{ cartStore.discount }}</span>
          </div>

          <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>${{ cartStore.subtotal }}</span>
            </div>
            <div class="flex justify-between">
              <span>Shipping</span>
              <span>${{ selectedDeliveryOption?.price || 0 }}</span>
            </div>
            <div class="flex justify-between font-semibold text-lg">
              <span>Total</span>
              <span>${{ total }}</span>
            </div>
          </div>

          <button
            type="submit"
            class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 disabled:opacity-50"
            :disabled="processing"
          >
            <span v-if="processing">Processing...</span>
            <span v-else>Place Order</span>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { useCartStore } from '@/Stores/cartStore'

const cartStore = useCartStore()
const processing = ref(false)

const deliveryOptions = [
  {
    id: 'standard',
    name: 'Standard Delivery',
    description: 'Delivery within 3-5 business days',
    price: 5.99,
  },
  {
    id: 'express',
    name: 'Express Delivery',
    description: 'Delivery within 1-2 business days',
    price: 14.99,
  },
]

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  address: '',
  address_2: '',
  city: '',
  postal_code: '',
  delivery_option: '',
})

const selectedDeliveryOption = computed(() => {
  return deliveryOptions.find(option => option.id === form.value.delivery_option)
})

const total = computed(() => {
  return cartStore.total + (selectedDeliveryOption.value?.price || 0)
})

const submitOrder = async () => {
  processing.value = true
  try {
    await router.post(route('customer.orders.store'), {
      ...form.value,
      items: cartStore.items,
      coupon: cartStore.coupon,
      subtotal: cartStore.subtotal,
      discount: cartStore.discount,
      shipping: selectedDeliveryOption.value?.price,
      total: total.value,
    })
  } catch (error) {
    console.error('Error placing order:', error)
  } finally {
    processing.value = false
  }
}
</script> 