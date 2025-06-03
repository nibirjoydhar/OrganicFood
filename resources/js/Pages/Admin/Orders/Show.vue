<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <!-- Order Header -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-2xl font-bold">Order #{{ order.order_number }}</h1>
          <div
            class="px-4 py-2 rounded-full text-sm font-medium"
            :class="{
              'bg-yellow-100 text-yellow-800': ['pending', 'payment_pending'].includes(order.status),
              'bg-green-100 text-green-800': ['processing', 'shipped', 'delivered'].includes(order.status),
              'bg-red-100 text-red-800': ['payment_failed', 'cancelled'].includes(order.status),
            }"
          >
            {{ formatStatus(order.status) }}
          </div>
        </div>

        <!-- Update Status Form -->
        <div v-if="Object.keys(availableStatuses).length > 0" class="mt-4 border-t pt-4">
          <h2 class="text-lg font-semibold mb-4">Update Status</h2>
          <form @submit.prevent="updateStatus" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                New Status
              </label>
              <select
                v-model="form.status"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                required
              >
                <option value="">Select Status</option>
                <option
                  v-for="(label, value) in availableStatuses"
                  :key="value"
                  :value="value"
                >
                  {{ label }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Comment (optional)
              </label>
              <textarea
                v-model="form.comment"
                rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                placeholder="Add a comment about this status change..."
              ></textarea>
            </div>
            <div>
              <button
                type="submit"
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700"
                :disabled="processing"
              >
                <span v-if="processing">Updating...</span>
                <span v-else>Update Status</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Order Timeline -->
        <div class="mt-6 border-t pt-6">
          <h2 class="text-lg font-semibold mb-4">Order Timeline</h2>
          <div class="relative">
            <!-- Progress Line -->
            <div class="absolute left-4 top-5 bottom-5 w-0.5 bg-gray-200"></div>

            <!-- Timeline Items -->
            <div class="space-y-8">
              <div
                v-for="(event, index) in order.timeline"
                :key="index"
                class="relative flex items-start"
              >
                <!-- Status Icon -->
                <div
                  class="absolute left-0 w-8 h-8 rounded-full flex items-center justify-center"
                  :class="{
                    'bg-green-100': ['processing', 'shipped', 'delivered'].includes(event.status),
                    'bg-yellow-100': ['pending', 'payment_pending'].includes(event.status),
                    'bg-red-100': ['payment_failed', 'cancelled'].includes(event.status),
                  }"
                >
                  <svg
                    v-if="['processing', 'shipped', 'delivered'].includes(event.status)"
                    class="w-4 h-4 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  <svg
                    v-else-if="['pending', 'payment_pending'].includes(event.status)"
                    class="w-4 h-4 text-yellow-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                  <svg
                    v-else
                    class="w-4 h-4 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </div>

                <!-- Event Content -->
                <div class="ml-12">
                  <div class="flex items-center">
                    <p class="font-medium">{{ formatStatus(event.status) }}</p>
                    <span class="ml-2 text-sm text-gray-500">
                      {{ new Date(event.created_at).toLocaleString() }}
                    </span>
                  </div>
                  <p v-if="event.comment" class="mt-1 text-gray-600">
                    {{ event.comment }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Details -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Customer Information -->
        <div class="p-6">
          <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600">Name</p>
              <p class="font-medium">{{ order.first_name }} {{ order.last_name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Email</p>
              <p class="font-medium">{{ order.email }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Phone</p>
              <p class="font-medium">{{ order.phone }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Delivery Method</p>
              <p class="font-medium">{{ formatDeliveryMethod(order.delivery_method) }}</p>
            </div>
            <div class="col-span-2">
              <p class="text-sm text-gray-600">Address</p>
              <p class="font-medium">
                {{ order.address }}
                <span v-if="order.address_2">{{ order.address_2 }}</span>
              </p>
              <p class="font-medium">{{ order.city }}, {{ order.postal_code }}</p>
            </div>
          </div>
        </div>

        <!-- Items -->
        <div class="border-t p-6">
          <h2 class="text-lg font-semibold mb-4">Order Items</h2>
          <div class="space-y-4">
            <div
              v-for="item in order.items"
              :key="item.id"
              class="flex items-center gap-4"
            >
              <img
                :src="item.product.images[0]"
                :alt="item.product.name"
                class="w-16 h-16 object-cover rounded-lg"
              />
              <div class="flex-1">
                <h3 class="font-medium">{{ item.product.name }}</h3>
                <p class="text-sm text-gray-600">
                  {{ item.quantity }} x ${{ item.price }}
                </p>
              </div>
              <p class="font-medium">${{ item.quantity * item.price }}</p>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="border-t p-6 bg-gray-50">
          <div class="space-y-2">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>${{ order.subtotal }}</span>
            </div>
            <div class="flex justify-between">
              <span>Shipping</span>
              <span>${{ order.shipping }}</span>
            </div>
            <div v-if="order.discount > 0" class="flex justify-between text-green-600">
              <span>Discount</span>
              <span>-${{ order.discount }}</span>
            </div>
            <div class="flex justify-between font-semibold text-lg pt-2 border-t">
              <span>Total</span>
              <span>${{ order.total }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  order: Object,
  availableStatuses: Object,
})

const processing = ref(false)
const form = ref({
  status: '',
  comment: '',
})

const updateStatus = async () => {
  processing.value = true
  try {
    await router.post(route('admin.orders.update-status', props.order.id), form.value)
    form.value = { status: '', comment: '' }
  } finally {
    processing.value = false
  }
}

const formatStatus = (status) => {
  return status
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

const formatDeliveryMethod = (method) => {
  switch (method) {
    case 'standard':
      return 'Standard Delivery (3-5 business days)'
    case 'express':
      return 'Express Delivery (1-2 business days)'
    default:
      return method
  }
}
</script> 