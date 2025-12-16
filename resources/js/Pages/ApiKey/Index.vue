<script setup>
import { actionButtonClass, primaryVariantClass } from '@/constants';
import { formatDate } from '../helpers';


const props = defineProps({
    items: Array,
    headers: Array
})

const obscureText = (text, char='*')=>{
    if (!text) return '';
    return char.repeat(text.length);
}

const keys = computed(()=>{
    return props.items.map(item=>{
        return {
            ...item,
            last_used_at: formatDate(item.last_used_at, {mode: 'relative'})
        }
    })
})

const updateKeys = ()=>{
    
}

</script>

<template>
<AppLayout title="Clés api">

    <template #header>
        <div class="flex justify-between">
            <p>Mes clés</p>
            <PrimaryButton @click.prevent="updateKeys" >
                Renegerer les clés
            </PrimaryButton>
        </div>
    </template>

    <Container >

        <DataTable :headers="headers" :items="keys" >

            <template #item.key_prefix="{item}">
                <div class=" space-x-4">
                    <p>{{ obscureText(item.key_prefix) }}</p>
                </div>
            </template>
            <!-- <template #item.actions="{item}">
                <div class=" space-x-4">
                    <Link dense :href="route('user.apiKeys.show', item.id)" 
                        :class="[actionButtonClass, primaryVariantClass]" > 
                        <ViewIcon size="20" /> 
                    </Link>
                </div>
            </template> -->
        </DataTable>
    </Container>
</AppLayout>
</template>