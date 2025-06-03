import { defineStore } from 'pinia'
import { router } from '@inertiajs/vue3'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    loading: false,
    coupon: null,
  }),

  getters: {
    totalItems: (state) => state.items.reduce((sum, item) => sum + item.quantity, 0),
    
    subtotal: (state) => state.items.reduce(
      (sum, item) => sum + (item.price * item.quantity),
      0
    ),
    
    discount: (state) => {
      if (!state.coupon) return 0
      return state.coupon.type === 'percentage'
        ? (state.subtotal * state.coupon.value) / 100
        : state.coupon.value
    },
    
    total: (state) => {
      const total = state.subtotal - state.discount
      return Math.max(total, 0)
    },
  },

  actions: {
    async fetchCart() {
      this.loading = true
      try {
        const response = await router.get(route('customer.cart.get'), {}, {
          preserveState: true,
          preserveScroll: true,
        })
        this.items = response.data.items
        this.coupon = response.data.coupon
      } catch (error) {
        console.error('Error fetching cart:', error)
      } finally {
        this.loading = false
      }
    },

    async addItem(productId, quantity = 1) {
      this.loading = true
      try {
        await router.post(route('customer.cart.add'), {
          product_id: productId,
          quantity: quantity,
        })
        await this.fetchCart()
      } catch (error) {
        console.error('Error adding item to cart:', error)
      } finally {
        this.loading = false
      }
    },

    async updateQuantity(productId, quantity) {
      if (quantity < 1) return
      this.loading = true
      try {
        await router.patch(route('customer.cart.update', productId), {
          quantity: quantity,
        })
        await this.fetchCart()
      } catch (error) {
        console.error('Error updating cart quantity:', error)
      } finally {
        this.loading = false
      }
    },

    async removeItem(productId) {
      this.loading = true
      try {
        await router.delete(route('customer.cart.remove', productId))
        await this.fetchCart()
      } catch (error) {
        console.error('Error removing item from cart:', error)
      } finally {
        this.loading = false
      }
    },

    async applyCoupon(code) {
      this.loading = true
      try {
        await router.post(route('customer.cart.coupon'), { code })
        await this.fetchCart()
      } catch (error) {
        console.error('Error applying coupon:', error)
      } finally {
        this.loading = false
      }
    },

    async removeCoupon() {
      this.loading = true
      try {
        await router.delete(route('customer.cart.coupon'))
        await this.fetchCart()
      } catch (error) {
        console.error('Error removing coupon:', error)
      } finally {
        this.loading = false
      }
    },
  },
}) 