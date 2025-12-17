<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue'; // Assuming a generic select component exists or will be created.

const props = defineProps({
    merchant: Object,
    fee_payer: String,
});

const form = useForm({
    _method: 'POST',
    fee_payer: props.fee_payer,
});

const updateFeeSettings = () => {
    form.post(route('user.merchants.settings.store', { merchant: props.merchant.id }), {
        errorBag: 'updateFeeSettings',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateFeeSettings">
        <template #title>
            Fee Settings
        </template>

        <template #description>
            Configure who pays the transaction fees for this merchant account.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="fee_payer" value="Fees Payer" />
                <SelectInput
                    id="fee_payer"
                    v-model="form.fee_payer"
                    class="mt-1 block w-full"
                    :options="[
                        { value: 'merchant', label: 'Merchant Pays' },
                        { value: 'customer', label: 'Customer Pays' },
                    ]"
                />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
