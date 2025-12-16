<script setup>
import { BadgePlusIcon } from 'lucide-vue-next';

const showDialog = ref(false)
const toggleShowDialog = ()=>{
    showDialog.value = !showDialog.value
}

</script>

<template>
    <div class="w-8 text-white flex justify-center items-center ">
        <AuthButton dense @click.prevent="toggleShowDialog" >
            <BadgePlusIcon size="20" />
        </AuthButton>
    </div>

    <Modal :show="showDialog" closeable @close="toggleShowDialog" >
        <div class="px-4 py-2" >
            <CreateMerchantForm @created="toggleShowDialog" />
        </div>
    </Modal>

</template>