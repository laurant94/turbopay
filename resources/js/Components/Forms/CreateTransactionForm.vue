<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    customers: Array,
    currencies: Array
})

const form = useForm({
    customer_id: null,
    amount: 0,
    currency: "",
    callback_url: null,
    description: null,

})

const emit = defineEmits(['created'])

const submitCustomer = ()=>{
    form.post(route('user.transactions.store'), {
        onSuccess: ()=>{
            emit("created")
        }
    })
}

</script>

<template>
    <div class="space-y-4">
        <SelectInput 
            label="Client"
            v-model="form.customer_id"
            :error="form.errors.customer_id"
            :options="customers"
            option-label="fullname"
            option-value="id"
            required
        />

        <div class="flex justify-end space-x-2">

            <div class="w-2/3" >
                <AuthInput label="Montant"
                    type="number"
                    v-model="form.amount"
                    :error="form.errors.amount"
                    required
                />
            </div>
            <div class="w-1/3" >
                <SelectInput 
                    label="Devise"
                    :options="currencies"
                    v-model="form.currency"
                    :error="form.errors.currency"
                    option-label="name"
                    option-value="code"
                    required
                />
            </div>
            

        </div>
        <AuthInput label="Lien de retour (Optionel)"
            type="text"
            v-model="form.callback_url"
            :error="form.errors.callback_url"
            required
        />
        <textarea label="Description"
            v-model="form.description"
            :error="form.errors.description"
            rows="4"
            class="w-full rounded border-gray-300"
            required
        />

        <div class="flex items-center justify-end space-x-4">
            <AuthButton type="button" variant="secondary" :disabled="form.processing" >Annuler</AuthButton>
            <AuthButton type="button" :disabled="form.processing" @click.prevent="submitCustomer" 
                variant="primary" >{{ form.processing ? 'En cours...' : 'Enregistré' }}</AuthButton>

        </div>
    </div>
</template>