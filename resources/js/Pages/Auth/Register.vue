<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthInput from '@/Components/Auth/AuthInput.vue';
import AuthButton from '@/Components/Auth/AuthButton.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="$t('auth.register.title')" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-4">
        <div class="relative w-full max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 shadow-2xl rounded-lg overflow-hidden">
                <!-- Left Visual Column -->
                <div class="hidden md:flex flex-col justify-center items-center bg-primary-600 text-white p-12">
                    <img src="https://placehold.co/300x100/ffffff/6366f1?text=Hospitality" alt="Logo" class="mb-8 w-48">
                    <h1 class="text-3xl font-bold mb-4 text-center">{{ $t('auth.register.slogan') }}</h1>
                    <p class="text-primary-200 text-center">{{ $t('auth.register.description') }}</p>
                </div>

                <!-- Right Form Column -->
                <div class="flex flex-col justify-center p-8 bg-white dark:bg-gray-800">
                    <AuthCard>
                        <div class="w-full">
                            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200">{{ $t('auth.register.heading') }}</h2>
                            <form @submit.prevent="submit">
                                <div class="space-y-5">
                                    <AuthInput
                                        :label="$t('auth.register.name_label')"
                                        type="text"
                                        v-model="form.name"
                                        :error="form.errors.name"
                                        required
                                    />
                                    <AuthInput
                                        :label="$t('auth.register.email_label')"
                                        type="email"
                                        v-model="form.email"
                                        :error="form.errors.email"
                                        required
                                    />
                                    <AuthInput
                                        :label="$t('auth.register.password_label')"
                                        type="password"
                                        v-model="form.password"
                                        :error="form.errors.password"
                                        required
                                    />
                                    <AuthInput
                                        :label="$t('auth.register.password_confirmation_label')"
                                        type="password"
                                        v-model="form.password_confirmation"
                                        :error="form.errors.password_confirmation"
                                        required
                                    />
                                </div>

                                <div class="mt-8">
                                    <AuthButton :disabled="form.processing">
                                        <span v-if="form.processing" class="animate-spin h-5 w-5 mr-3 border-t-2 border-b-2 border-primary-50 rounded-full"></span>
                                        {{ $t('auth.register.register_button') }}
                                    </AuthButton>
                                </div>
                            </form>
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $t('auth.register.already_registered') }}
                                    <Link :href="route('login')" class="font-medium text-primary-600 hover:text-primary-500">
                                        {{ $t('auth.register.login_link') }}
                                    </Link>
                                </p>
                            </div>
                        </div>
                    </AuthCard>
                </div>
            </div>
        </div>
    </div>
</template>
