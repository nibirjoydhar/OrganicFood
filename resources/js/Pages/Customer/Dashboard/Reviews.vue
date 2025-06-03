<template>
    <CustomerDashboardLayout>
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-900">My Reviews</h1>

            <div class="mt-6">
                <div v-if="reviews.data.length" class="space-y-6">
                    <div v-for="review in reviews.data" :key="review.id" class="bg-white border rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img :src="review.product.image" :alt="review.product.name" class="h-12 w-12 object-cover rounded-lg">
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ review.product.name }}</h3>
                                    <p class="text-sm text-gray-500">Reviewed on {{ formatDate(review.created_at) }}</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                class="text-red-600 hover:text-red-900"
                                @click="deleteReview(review)"
                            >
                                Delete
                            </button>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex">
                                    <StarIcon
                                        v-for="rating in 5"
                                        :key="rating"
                                        :class="[
                                            rating <= review.rating ? 'text-yellow-400' : 'text-gray-300',
                                            'h-5 w-5'
                                        ]"
                                    />
                                </div>
                                <p class="ml-2 text-sm text-gray-500">{{ review.rating }} out of 5 stars</p>
                            </div>
                            <p class="mt-4 text-sm text-gray-600">{{ review.comment }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-4 text-gray-500">No reviews found.</div>

                <!-- Pagination -->
                <div v-if="reviews.data.length" class="mt-6">
                    <Pagination :links="reviews.links" />
                </div>
            </div>
        </div>
    </CustomerDashboardLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import CustomerDashboardLayout from '@/Layouts/CustomerDashboardLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import StarIcon from '@/Components/Icons/StarIcon.vue';

const props = defineProps({
    reviews: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const deleteReview = (review) => {
    if (confirm('Are you sure you want to delete this review?')) {
        router.delete(route('customer.reviews.destroy', review.id));
    }
};
</script> 