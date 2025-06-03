<template>
  <div class="flex items-center">
    <button
      v-for="star in 5"
      :key="star"
      type="button"
      class="p-1"
      :class="{ 'cursor-default': readonly }"
      @click="!readonly && updateRating(star)"
      @mouseover="!readonly && (hoverRating = star)"
      @mouseleave="!readonly && (hoverRating = 0)"
    >
      <svg
        class="w-5 h-5"
        :class="{
          'text-yellow-400': (hoverRating || modelValue) >= star,
          'text-gray-300': (hoverRating || modelValue) < star
        }"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
        />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  readonly: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue'])

const hoverRating = ref(0)

const updateRating = (rating) => {
  emit('update:modelValue', rating === props.modelValue ? 0 : rating)
}
</script> 