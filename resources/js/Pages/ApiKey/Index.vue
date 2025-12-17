<script setup>
import { actionButtonClass, primaryVariantClass } from '@/constants';
import { formatDate } from '../helpers';
import { router } from '@inertiajs/vue3';


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
            created_at: formatDate(item.created_at, {mode: 'datetime'}),
            last_used_at: item.last_used_at ? formatDate(item.last_used_at, {mode: 'relative'}) : "Jamais"
        }
    })
})

const newKeys = ref({
    private: null,
    public: null,
})
const showKeysModal = ref(false)
const copyed = ref(false)

const updateKeys = ()=>{
    router.post(route('user.apiKeys.store'), {}, {
        onSuccess: (data)=>{
            if(data.props.flash?.datas){
                let d = data.props.flash?.datas;
                newKeys.value = {
                    private: d.private?.key,
                    public: d.public?.key,
                };
                showKeysModal.value = true;
            }
        }
    })
}

</script>

<template>
<AppLayout title="Clés api">

    <template #header>
        <div class="flex justify-between">
            <p>Mes clés</p>
            <PrimaryButton type="button" @click.prevent="updateKeys" >
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


    <Modal :show="showKeysModal" >
        <div class="px-4 py-4" >
            <div class="py-4">
                <p>Veillez copier ses clées en lieu sure car elle ne sont affichées qu'une seule fois</p>
            </div>
            <div class="space-y-4">
                <p class="space-x-4"><strong>Clé publique:</strong> <em>{{ newKeys.public }}</em> </p>
                <p class="space-x-4"><strong>Clé privée:</strong> <em>{{ newKeys.private }}</em> </p>
            </div>
            <div class="spaxe-x-4 flex items-center mt-2">
                <input id="copy" type="checkbox" v-model="copyed"
                    class="rounded ring-1"
                /> 
                <label for="copy" class="ml-2">J'ai copié les clés</label>
            </div>
        </div>

        <template #actions>
            <div class="px-2 py-2">
                <AuthButton dense :disabled="copyed==false" @click.prevent="showKeysModal=false;copyed=false" >
                    Fermer
                </AuthButton>
            </div>
        </template>
    </Modal>

</AppLayout>
</template>