<script setup>
import { actionButtonClass, primaryVariantClass } from '@/constants';
import { formatDate } from '../helpers';
import { Link } from '@inertiajs/vue3';
import { ViewIcon, NotebookPenIcon, Trash2Icon } from 'lucide-vue-next';
import { trans } from 'matice';


const props = defineProps({
    items: Object,
    customers: Array,
    headers: Array,
    currencies: Array,
})

const transactions = computed(()=>{
    return props.items.data.map((item)=> {
        return {
            ...item,
            created_at: formatDate(item.created_at, {locale: 'fr' ,mode: 'datetime'}),
            status: trans(`widgets.status.${item.status}`),
            customer_id: item.customer?.fullname ?? "Client inconnu"
        }
    })
})

</script>

<template>
<AppLayout title="Mes Transactions">
    <template #header>
        <div class="w-full flex justify-end">
            <CreateTransactionButton :customers="customers" :currencies="currencies" />
        </div>
    </template>

    <Container title="Mes Transactions">

        <DataTable :headers="headers" :items="transactions" :links="items.links" >

            <template #item.quick_details="{item}">
                <Link :href="route('user.customers.show', item.id)" >
                    {{ item.quick_details }}
                </Link>
            </template>

            <template #item.actions="{item}">
                <div class=" space-x-4">
                    <Link dense :href="route('user.transactions.show', item.id)" 
                        :class="[actionButtonClass, primaryVariantClass]"
                    > 
                        <ViewIcon size="20" /> 
                    </Link>
                </div>
            </template>
        </DataTable>
    </Container>
</AppLayout>
</template>