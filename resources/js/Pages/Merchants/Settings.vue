<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FeeSettingsForm from '@/Pages/Profile/Partials/FeeSettingsForm.vue'; // The form is in the Profile folder, which is fine.
import SectionBorder from '@/Components/SectionBorder.vue';

defineProps({
    merchant: Object,
    fee_payer: String,
});
</script>

<template>
    <AppLayout :title="`Settings for ${merchant.name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings for {{ merchant.name }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <FeeSettingsForm :merchant="merchant" :fee_payer="fee_payer" />
                    <SectionBorder />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
