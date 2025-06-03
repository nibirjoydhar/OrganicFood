<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- ... existing navigation ... -->
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

        <!-- Toast Notifications -->
        <Toast ref="toast" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Toast from '@/Components/Toast.vue';
import '../plugins/echo';

const toast = ref(null);
const page = usePage();

// Show flash messages as toasts
onMounted(() => {
    // Watch for flash messages
    if (page.props.flash.success) {
        toast.value.addToast({
            type: 'success',
            message: page.props.flash.success,
        });
    }

    if (page.props.flash.error) {
        toast.value.addToast({
            type: 'error',
            message: page.props.flash.error,
        });
    }

    // Listen for real-time notifications if user is authenticated
    if (page.props.auth.user) {
        window.Echo.private(`App.Models.User.${page.props.auth.user.id}`)
            .notification((notification) => {
                toast.value.addToast({
                    type: 'info',
                    title: 'New Notification',
                    message: notification.message,
                    duration: 0,
                });
            });
    }
});
</script> 