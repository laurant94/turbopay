<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthButton from '@/Components/Auth/AuthButton.vue';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head :title="$t('auth.verify_email.title')" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <AuthCard class="w-full sm:max-w-md">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ $t('auth.verify_email.verification_message') }}
            </div>

            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $t('auth.verify_email.verification_link_sent') }}
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <AuthButton :disabled="form.processing">
                        {{ $t('auth.verify_email.resend_button') }}
                    </AuthButton>

                    <div>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        >
                            {{ $t('auth.verify_email.logout_button') }}
                        </Link>
                    </div>
                </div>
            </form>
        </AuthCard>
    </div>
</template>
