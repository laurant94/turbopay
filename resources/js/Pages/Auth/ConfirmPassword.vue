<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthInput from '@/Components/Auth/AuthInput.vue';
import AuthButton from '@/Components/Auth/AuthButton.vue';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head :title="$t('auth.confirm_password.title')" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <AuthCard class="w-full sm:max-w-md">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ $t('auth.confirm_password.secure_area_message') }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <AuthInput
                        :label="$t('auth.confirm_password.password_label')"
                        type="password"
                        v-model="form.password"
                        :error="form.errors.password"
                        required
                        autofocus
                    />
                </div>

                <div class="flex justify-end mt-4">
                    <AuthButton :disabled="form.processing">
                        <span v-if="form.processing" class="animate-spin h-5 w-5 mr-3 border-t-2 border-b-2 border-primary-50 rounded-full"></span>
                        {{ $t('auth.confirm_password.confirm_button') }}
                    </AuthButton>
                </div>
            </form>
        </AuthCard>
    </div>
</template>
