<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import DataTable from '@/Components/Widgets/DataTable.vue';
import { computed } from 'vue';
import { trans } from 'matice';
import { formatDate } from '../helpers';

const props = defineProps({
    webhookEvents: Object,
});

const headers = [
    { key: 'event', label: 'Événement' },
    { key: 'endpoint_url', label: 'Endpoint URL' },
    { key: 'response_status', label: 'Statut Réponse' },
    { key: 'attempts', label: 'Tentatives' },
    { key: 'sent_at', label: 'Envoyé le' },
    { key: 'payload_preview', label: 'Payload (Aperçu)' },
];

const formattedWebhookEvents = computed(() => {
    return props.webhookEvents.data.map((item) => {
        return {
            ...item,
            endpoint_url: item.webhook_endpoint ? item.webhook_endpoint.url : 'N/A',
            sent_at: item.sent_at ? formatDate(item.sent_at, { locale: 'fr', mode: 'datetime' }) : 'En attente',
            payload_preview: item.payload ? JSON.stringify(item.payload, null, 2) : 'N/A',
        };
    });
});

</script>

<template>
    <AppLayout title="Événements Webhook envoyés">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Historique des Événements Webhook
            </h2>
        </template>

        <Container title="Événements Webhook envoyés">
            <DataTable :headers="headers" :items="formattedWebhookEvents" :links="webhookEvents.links">
                <template #item.payload_preview="{ value }">
                    <pre class="text-xs max-h-24 overflow-y-auto bg-gray-100 dark:bg-gray-700 p-2 rounded">{{ value }}</pre>
                </template>
            </DataTable>
        </Container>
    </AppLayout>
</template>
