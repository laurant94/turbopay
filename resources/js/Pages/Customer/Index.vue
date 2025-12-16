<script setup>
import { actionButtonClass, primaryVariantClass } from '@/constants';
import { formatDate } from '../helpers';
import { Link } from '@inertiajs/vue3';
import { ViewIcon, NotebookPenIcon, Trash2Icon } from 'lucide-vue-next';


const props = defineProps({
    items: Object,
    headers: Array,
})

const customers = computed(()=>{
    return props.items.data.map((item)=> {
        return {
            ...item,
            created_at: formatDate(item.created_at, {locale: 'fr' ,mode: 'datetime'})
        }
    })
})

</script>

<template>
<AppLayout title="Mes clients">
    <template #header>
        <div class="w-full flex justify-end">
            <CreateCustomerButton />
        </div>
    </template>

    <Container title="Mes clients">

        <DataTable :headers="headers" :items="customers" :links="items.links" >

            <template #item.quick_details="{item}">
                <Link :href="route('user.customers.show', item.id)" >
                    {{ item.quick_details }}
                </Link>
            </template>

            <template #item.actions="{item}">
                <div class=" space-x-4">
                    <Link dense :href="route('user.customers.show', item.id)" 
                        :class="[actionButtonClass, primaryVariantClass]" > 
                        <ViewIcon size="20" /> 
                    </Link>
                </div>
            </template>
        </DataTable>
    </Container>
</AppLayout>
</template>