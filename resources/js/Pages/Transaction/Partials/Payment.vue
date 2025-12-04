
<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { asset } from '@/Pages/helpers';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, provide, ref, watch } from 'vue';


const props = defineProps({
    transaction: Object,
})

const form = useForm({
    customer: props.transaction.customer ?? {
        phone: "",
        firstname: "",
        lastname: ""
    },
    amount: props.transaction?.amount ?? 0,
    provider: "mtnbenin",
    token: props.transaction.payment_token,
})
const customerForm = useForm(props.transaction.customer)

const hasInitiateTransaction = ref(false);
const hasSuccessfulpayment = ref(false)
const hasChangeEmail = ref(false);

const toggleChangeEmail = ()=>{
    hasChangeEmail.value = !hasChangeEmail.value
}

const errors = {
    "Transaction already processed": "Cette transaction est terminée",
    "Invalid or expired token": "Cette transaction est invalide ou a déjà expirée"
}

const error = ref(null)

const noCustomer = computed(() => !(form.customer?.email && form.customer?.firstname &&
    form.customer?.lastname && form.customer?.phone))

const availableTransaction = computed(() => !noCustomer.value && form.amount > 0)

const requestPayment = () => {
    form.post(route('process'), {
        preserveScroll: true,
        onSuccess: (datas) => {
            console.log(datas.props.flash.datas)
            hasInitiateTransaction.value = datas.props?.flash?.datas?.success ? true : false;
        },
    })
}

const updateCustomer = ()=>{
    customerForm.post(route('process.update_customer', customerForm.id), {
        onSuccess: (data)=> location.reload()
    })
}

const confirmPayment = () => {
    router.get(route('confirm', props.transaction.id), {}, {
        preserveScroll: true,
        onSuccess: (datas) => {
            // console.log(datas.props.flash.datas)
            let data = datas.props?.flash?.datas;

            hasSuccessfulpayment.value = (data && data.success) ? true : false;
            error.value = datas.props?.flash?.datas?.message ?? null;

            if (hasSuccessfulpayment.value) {
                if (props.transaction.callback_url) {
                    location.href = props.transaction.callback_url + `?status=${data.data?.status}&canceled=${false}&id=${props.transaction.id}`
                }else{
                    location.href = route("default-callback", {
                        status: data.data?.status,
                        canceled: false,
                        id: props.transaction.id
                    })
                }
            }
        }
    })
}

const timer = ref(null);
const makeVerify = () => {
    timer.value = setInterval(confirmPayment, 5000);

    if (hasSuccessfulpayment.value) {
        console.log("finished")
        clearInterval(timer.value);
        
    }
}

const icons = {
    mtnbenin: asset("assets/icons/mtn_bj.png"),
    moovbenin: asset("assets/icons/moov_bj.png"),
}

watch(hasInitiateTransaction, (value) => {
    makeVerify()
})

</script>

<template>
    <div  >
        <!-- Email Info -->
        <div class="mb-4">
            <p class="text-xs text-gray-600">Votre reçu vous sera envoyé à cette adresse <span class="font-medium">{{
                form.customer.email }}</span></p>
            <button @click.prevent="toggleChangeEmail" v-if="error!=null" class="text-xs text-purple-600 hover:underline mt-1">Je veux utiliser
                une autre adresse email</button>
        </div>
        <div  v-if="hasChangeEmail">
            <TextInput type="text" class="rounded-md" v-model="customerForm.email" />
            <div class="flex justify-end space-x-2 mt-2">
                <InputError :message="customerForm.errors.email" />
                <PrimaryButton @click.prevent="updateCustomer" >Changer</PrimaryButton>
                <SecondaryButton @click.prevent="toggleChangeEmail" >Annuler</SecondaryButton>
            </div>
        </div>

        <!-- Operator Selection -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Choisissez votre opérateur</label>
            <select v-model="form.provider"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="mtnbenin">MTN Mobile Money</option>
                <option value="moovbenin">Airtel Money</option>
            </select>
        </div>

        <!-- Phone Number -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Numéro de téléphone</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <img class="w-6" :src="icons[form.provider]" alt="method">
                </div>
                <TextInput type="tel" placeholder="Votre numéro de téléphone" class="pl-10 pr-10 "  v-model="form.customer.phone" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M12 17h.01M12 12h.01M12 12h.01" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Fee Notice -->
        <div class="bg-purple-50 p-3 rounded-md mb-4">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2 mt-0.5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M12 17h.01M12 12h.01M12 12h.01" />
                </svg>
                <div class="text-xs text-gray-700">
                    <strong>42 CFA</strong> de frais supplémentaires sont appliqués sur votre paiement.
                </div>
            </div>
        </div>

        <!-- Pay Button -->
        <button class="w-full disabled:bg-green-500 bg-green-700 hover:bg-green-800 text-white 
                        font-medium py-3 px-4 rounded-md flex items-center justify-between transition-colors"
            :disabled="!availableTransaction" @click.prevent="requestPayment">
            <template v-if="form.processing">
                <span class="flex justify-center">
                    Loading...
                </span>
            </template>
            <template v-else>
                <span>PAYER {{ form.amount }} CFA</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </template>

        </button>
    </div>
</template>