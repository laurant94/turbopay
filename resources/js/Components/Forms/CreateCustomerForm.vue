<script setup>
import { useForm } from '@inertiajs/vue3';


const form = useForm({
    firstname: "",
    lastname: "",
    email: "",
    phone: ""
})

const emit = defineEmits(['created'])

const submitCustomer = ()=>{
    form.post(route('user.customers.store'), {
        onSuccess: ()=>{
            emit("created")
        }
    })
}

</script>

<template>
    <div class="space-y-4">
        <AuthInput label="Nom"
            type="text"
            v-model="form.firstname"
            :error="form.errors.firstname"
            required
        />
        <AuthInput label="Prénom"
            type="text"
            v-model="form.lastname"
            :error="form.errors.lastname"
            required
        />
        <AuthInput label="E-mail"
            type="email"
            v-model="form.email"
            :error="form.errors.email"
            required
        />
        <AuthInput label="Téléphone"
            type="tel"
            v-model="form.phone"
            :error="form.errors.phone"
            required
        />

        <div class="flex items-center justify-end space-x-4">
            <AuthButton type="button" variant="secondary" :disabled="form.processing" >Annuler</AuthButton>
            <AuthButton type="button" :disabled="form.processing" @click.prevent="submitCustomer" 
                variant="primary" >{{ form.processing ? 'En cours...' : 'Enregistré' }}</AuthButton>

        </div>
    </div>
</template>