<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, provide, ref, watch } from 'vue';
import Payment from './Partials/Payment.vue';
import Customer from './Partials/Customer.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// Émettre un événement pour fermer la modal
defineEmits(['close']);

const props = defineProps({
    transaction: Object
})

const hasCustomer = computed(() => (props.transaction.customer?.email && props.transaction.customer?.firstname &&
    props.transaction.customer?.lastname && props.transaction.customer?.phone))



</script>



<template>
    <Head title="Procéder au paiement" />
    <div class="fixed inset-0 bg-gray-700 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-tl-lg rounded-br-lg transform rotate-45"></div>
                </div>
                <div class="text-xs text-gray-600">
                    <div>MARCHAND: {{ transaction.merchant?.name }}</div>
                    <div>ID: {{ transaction.id }}</div>
                </div>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex">
                <!-- Left Panel - Payment Options -->
                <div class="w-1/3 border-r p-4">
                    <h3 class="font-bold mb-4">PAYER AVEC</h3>

                    <!-- Mobile Money Option -->
                    <div class="bg-gray-50 p-3 rounded-lg mb-4 cursor-pointer hover:bg-gray-100 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-2">
                                <div class="w-5 h-5 bg-indigo-900 rounded-sm flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0 2a2 2 0 100 4m-6 8a2 2 0 1112 0m-6-8a2 2 0 1112 0m-6 8h6m-6-8h6" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-indigo-800">Mobile Money</div>
                                    <div class="text-xs text-gray-500">Mtn</div>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Cancel Payment -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="font-medium">Annuler le paiement</span>
                        </div>
                        <div class="flex space-x-2 text-sm">
                            <!-- <span class="text-gray-600">en</span>
              <span class="px-1">|</span>
              <span class="text-purple-600 font-medium">fr</span> -->
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Payment Form -->
                <div class="w-2/3 p-4">
                    <template v-if="!hasCustomer" >
                        <Customer :merchantId="transaction.merchant_id" :transaction-id="transaction?.id" />
                    </template>
                    <template v-else-if="transaction.status !== 'pending'">
                        <div>
                            <center>
                                <p>Cette transaction est déjà achevé</p>
                                <PrimaryButton href="gogle.com" >Quitter</PrimaryButton>
                            </center>
                        </div>
                    </template>
                    <template v-else>
                        <Payment :transaction="transaction"  />
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 text-center text-xs text-gray-500 border-t">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v6h8z" />
                </svg>
                Secured by <span class="font-medium">QCT-Group</span>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Personnalisation des styles si nécessaire */
</style>