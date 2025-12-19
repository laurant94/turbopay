<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import DataTable from '@/Components/Widgets/DataTable.vue';
import { Link } from '@inertiajs/vue3';
import { ViewIcon, PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { actionButtonClass, primaryVariantClass, dangerVariantClass } from '@/constants';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    webhookEndpoints: Object,
});

const headers = [
    { key: 'url', label: 'URL' },
    { key: 'events', label: 'Événements' },
    { key: 'active', label: 'Actif' },
    { key: 'secret_preview', label: 'Secret (Preview)' },
    { key: 'actions', label: 'Actions' },
];

const formattedEndpoints = props.webhookEndpoints.data.map((endpoint) => {
    return {
        ...endpoint,
        events: endpoint.events && endpoint.events.length > 0
            ? endpoint.events.map(event => event.replace(/([A-Z])/g, ' $1').trim()).join(', ').substring(0, 30) + '...'
            : 'Tous',
        active: endpoint.active ? 'Oui' : 'Non',
        secret_preview: endpoint.secret ? endpoint.secret.substring(0, 10) + '...' : 'N/A',
    };
});

// Delete confirmation modal
const confirmingWebhookDeletion = ref(false);
const webhookToDelete = ref(null);

const confirmWebhookDeletion = (webhook) => {
    webhookToDelete.value = webhook;
    confirmingWebhookDeletion.value = true;
};

const deleteWebhook = () => {
    if (webhookToDelete.value) {
        router.delete(route('user.webhook-endpoints.destroy', webhookToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                confirmingWebhookDeletion.value = false;
                webhookToDelete.value = null;
            },
            onError: () => {
                // Handle error
            },
        });
    }
};
</script>

<template>
    <AppLayout title="Webhooks">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Gestion des Webhooks
                </h2>
                <Link :class="[actionButtonClass, primaryVariantClass]" :href="route('user.webhook-endpoints.create')" >
                    Ajouter un webhook
                </Link>
            </div>
        </template>

        <Container title="Mes Webhooks">
            <template #action>
                <Link :href="route('user.webhook-endpoints.create')"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Ajouter un Webhook
                </Link>
            </template>

            <DataTable :headers="headers" :items="formattedEndpoints" :links="webhookEndpoints.links">
                <template #item.actions="{ item }">
                    <div class="flex space-x-2">
                        <Link :href="route('user.webhook-endpoints.edit', item.id)"
                              :class="[actionButtonClass, primaryVariantClass]"
                              title="Modifier">
                            <PencilIcon size="20" />
                        </Link>
                        <button @click="confirmWebhookDeletion(item)"
                                :class="[actionButtonClass, dangerVariantClass]"
                                title="Supprimer">
                            <Trash2Icon size="20" />
                        </button>
                    </div>
                </template>
            </DataTable>
        </Container>

        <!-- Delete Webhook Confirmation Modal -->
        <Modal :show="confirmingWebhookDeletion" @close="confirmingWebhookDeletion = false">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Confirmer la suppression du Webhook
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Êtes-vous sûr de vouloir supprimer ce webhook ? Cette action est irréversible.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="confirmingWebhookDeletion = false">
                        Annuler
                    </SecondaryButton>
                    <DangerButton class="ml-3" @click="deleteWebhook">
                        Supprimer le Webhook
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
