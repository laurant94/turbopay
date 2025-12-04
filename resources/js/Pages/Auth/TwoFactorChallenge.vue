<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthCard from '@/Components/Auth/AuthCard.vue';
import AuthInput from '@/Components/Auth/AuthInput.vue';
import AuthButton from '@/Components/Auth/AuthButton.vue';

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const toggleRecovery = () => {
    recovery.value = !recovery.value;
    form.reset();
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <Head :title="$t('auth.two_factor_challenge.title')" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <AuthCard class="w-full sm:max-w-md">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                <template v-if="! recovery">
                    {{ $t('auth.two_factor_challenge.code_prompt') }}
                </template>
                <template v-else>
                    {{ $t('auth.two_factor_challenge.recovery_code_prompt') }}
                </template>
            </div>

            <form @submit.prevent="submit">
                <div v-if="! recovery">
                    <AuthInput
                        :label="$t('auth.two_factor_challenge.code_label')"
                        type="text"
                        inputmode="numeric"
                        v-model="form.code"
                        :error="form.errors.code"
                        autofocus
                        autocomplete="one-time-code"
                    />
                </div>
                <div v-else>
                    <AuthInput
                        :label="$t('auth.two_factor_challenge.recovery_code_label')"
                        type="text"
                        v-model="form.recovery_code"
                        :error="form.errors.recovery_code"
                        autocomplete="one-time-code"
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline cursor-pointer" @click.prevent="toggleRecovery">
                        <template v-if="! recovery">
                            {{ $t('auth.two_factor_challenge.use_recovery_code_button') }}
                        </template>
                        <template v-else>
                            {{ $t('auth.two_factor_challenge.use_authentication_code_button') }}
                        </template>
                    </button>
                </div>

                <div class="mt-4">
                     <AuthButton :disabled="form.processing">
                        <span v-if="form.processing" class="animate-spin h-5 w-5 mr-3 border-t-2 border-b-2 border-primary-50 rounded-full"></span>
                        {{ $t('auth.two_factor_challenge.login_button') }}
                    </AuthButton>
                </div>
            </form>
        </AuthCard>
    </div>
</template>
