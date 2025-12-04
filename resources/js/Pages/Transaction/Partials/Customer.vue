<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        merchantId: String,
        transactionId: [String, Number]
    })

    const form = useForm({
        firstname: "",
        lastname: "",
        email: "",
        phone: "",
        merchant_id: props.merchantId,
        transaction_id: props.transactionId,
    })

    const createCustomer = ()=>{
        form.post(route('process.create_customer'), {
            onSuccess: ()=> location.reload()
        })
    }

</script>

<template>
    <form @submit.prevent="createCustomer" >

        <div class="mb-4">
            <InputLabel  >Nom</InputLabel>
            <TextInput v-model="form.firstname" />
        </div>

        <div class="mb-4">
            <InputLabel  >Prénom</InputLabel>
            <TextInput v-model="form.lastname" />
        </div>

        <div class="mb-4">
            <InputLabel  >Email</InputLabel>
            <TextInput v-model="form.email" />
        </div>

        <div class="mb-4">
            <InputLabel  >Téléphone</InputLabel>
            <TextInput v-model="form.phone" />
        </div>

        <div class="flex justify-end">
            <PrimaryButton type="submit" >Continuer</PrimaryButton>
        </div>

    </form>
</template>