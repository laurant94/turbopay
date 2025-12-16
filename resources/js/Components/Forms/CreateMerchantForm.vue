<script setup>
import { useForm } from '@inertiajs/vue3';


const form = useForm({
    name: '',
})

const emit = defineEmits(['created'])

const submit = ()=>{
    form.post(route('user.merchants.store'), {
        onSuccess: ()=>{
            emit('created')
        }
    })
}

</script>

<template>
    <form @submit.prevent="submit" >
        <div class="mt-4 mb-8 text-lg font-bold">
            <p>Créer un nouveau compte marchant</p>
        </div>

        <div>
            <AuthInput label="Nom du compte marchant" 
                v-model="form.name"
            />
        </div>

        <div class=" flex justify-end mt-4">
            <AuthButton type="submit" variant="primary" >Creer</AuthButton>
        </div>

    </form>
</template>