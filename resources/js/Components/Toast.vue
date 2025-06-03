<template>
    <TransitionRoot
        appear
        show="true"
        as="template"
    >
        <div class="fixed z-50 top-0 right-0 p-4 pointer-events-none">
            <TransitionGroup
                enter="transform ease-out duration-300 transition"
                enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to="translate-y-0 opacity-100 sm:translate-x-0"
                leave="transition ease-in duration-100"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5 mb-3"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <CheckCircleIcon v-if="toast.type === 'success'" class="h-6 w-6 text-green-400" />
                                <ExclamationCircleIcon v-else-if="toast.type === 'error'" class="h-6 w-6 text-red-400" />
                                <InformationCircleIcon v-else class="h-6 w-6 text-blue-400" />
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p v-if="toast.title" class="text-sm font-medium text-gray-900">{{ toast.title }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ toast.message }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    type="button"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    @click="removeToast(toast.id)"
                                >
                                    <span class="sr-only">Close</span>
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </TransitionRoot>
</template>

<script setup>
import { ref } from 'vue';
import { TransitionRoot, TransitionGroup } from '@headlessui/vue';
import {
    CheckCircleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';

const toasts = ref([]);
let toastId = 0;

const addToast = (toast) => {
    const id = ++toastId;
    toasts.value.push({
        id,
        title: toast.title,
        message: toast.message,
        type: toast.type || 'info',
        duration: toast.duration || 5000,
    });

    if (toast.duration !== 0) {
        setTimeout(() => {
            removeToast(id);
        }, toast.duration || 5000);
    }
};

const removeToast = (id) => {
    const index = toasts.value.findIndex(toast => toast.id === id);
    if (index > -1) {
        toasts.value.splice(index, 1);
    }
};

// Expose methods to the template
defineExpose({
    addToast,
    removeToast,
});
</script> 