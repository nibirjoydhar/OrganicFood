<template>
    <CustomerDashboardLayout>
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-900">Profile Settings</h1>

            <form @submit.prevent="form.patch(route('customer.dashboard.profile.update'))" class="mt-6 space-y-6">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="phone" value="Phone" />
                    <TextInput
                        id="phone"
                        type="tel"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                    />
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-medium text-gray-900">Change Password</h2>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>

                    <div class="mt-6">
                        <InputLabel for="current_password" value="Current Password" />
                        <TextInput
                            id="current_password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.current_password"
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.current_password" />
                    </div>

                    <div class="mt-6">
                        <InputLabel for="new_password" value="New Password" />
                        <TextInput
                            id="new_password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.new_password"
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.new_password" />
                    </div>

                    <div class="mt-6">
                        <InputLabel for="new_password_confirmation" value="Confirm Password" />
                        <TextInput
                            id="new_password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.new_password_confirmation"
                            autocomplete="new-password"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Save Changes</PrimaryButton>

                    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </CustomerDashboardLayout>
</template>

<script setup>
import CustomerDashboardLayout from '@/Layouts/CustomerDashboardLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});
</script> 