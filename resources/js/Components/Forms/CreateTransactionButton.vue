<script setup>
import { BadgePlusIcon } from 'lucide-vue-next';

const props = defineProps({
    customers: Array,
    currencies: Array,
})

const showDialog = ref(false)
const toggleShowDialog = ()=>{
    showDialog.value = !showDialog.value
}

</script>

<template>
    <div class=" text-white flex justify-center items-center ">
        <AuthButton dense @click.prevent="toggleShowDialog" class="space-x-1" >
            <BadgePlusIcon size="20" />
            <i>Ajouter une transaction</i>
        </AuthButton>
    </div>

    <Modal :show="showDialog" closeable @close="toggleShowDialog" >
        <div class="px-4 py-2" >
            <CreateTransactionForm :customers="customers" :currencies="currencies" @created="toggleShowDialog" />
        </div>
    </Modal>

</template>