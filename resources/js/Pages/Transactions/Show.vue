<script setup>
import { statusClass } from '@/constants';
import { router } from '@inertiajs/vue3';
import { formatDate } from '../helpers';


const props = defineProps({
    item: Object
})
const formProcessing = ref(false)
const paymentDatas = ref(null)
const beforTokenCreationModal = ref(false)
const afterTokenCreationModal = ref(false)

const toggleBeforCreationModal = ()=>{
    beforTokenCreationModal.value = !beforTokenCreationModal.value;
}
const toggleAfterCreationModal = ()=>{
    afterTokenCreationModal.value = !afterTokenCreationModal.value;
}

const generatePaymentToken = ()=>{
    router.post(route('user.transactions.generate-token', props.item.id), {}, {
        preserveScroll: true,
        onBefore: ()=> formProcessing.value = true,
        onFinish: ()=> formProcessing.value = false,
        onSuccess: (data)=>{
            toggleBeforCreationModal()
            console.log(data.props?.flash?.datas)
            paymentDatas.value = data.props?.flash?.datas;
            toggleAfterCreationModal();
        }
    })
}

</script>

<template>
<AppLayout title="Details">

    <Container >
        <template #title>
            <div class="flex justify-start w-full">
                <strong>{{ item.amount }} {{ item.currency }}</strong> 
                <i :class="[statusClass(item.status)]" >
                    {{ $t(`widgets.status.${item.status}`) }}
                </i> 
            </div>
        </template>
        <template #actions>
            <AuthButton dense @click="toggleBeforCreationModal">
                Générer le lien de paiement
            </AuthButton>
        </template>
    </Container>


    <Modal :show="beforTokenCreationModal" title="Confirmation" closeable  @close="toggleBeforCreationModal">
        <div class="w-full flex justify-center py-4">
            <AuthButton @click.prevent="generatePaymentToken" :disabled="formProcessing">
                Générer maintenant
            </AuthButton>
        </div>
    </Modal>

    <Modal :show="afterTokenCreationModal">
        <div class="px-2 py-4 space-y-4">
            <h3>Informations de paiement</h3>
            <h4>Ce lien expire {{ formatDate(paymentDatas.expires_at, {mode: 'relative'}) }}</h4>
            <AuthInput 
            label="Token de paiement"
            v-model="paymentDatas.token"
            readonly
            />
            
            <AuthInput 
            label="Lien de paiement"
            v-model="paymentDatas.payment_url"
            readonly
            />
            
        </div>
        <template #actions>
            <AuthButton @click.prevent="toggleAfterCreationModal" >Fermer</AuthButton>
        </template>
    </Modal>

</AppLayout>
</template>