<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import DataTable from '@/Components/Widgets/DataTable.vue';
import { computed } from 'vue';
import { trans } from 'matice';
import { formatDate } from '../helpers';

const props = defineProps({
    events: Object,
});

const headers = [
    { key: 'event_type', label: 'Type d\'événement' },
    { key: 'user_id', label: 'ID Utilisateur' },
    { key: 'payload', label: 'Données' },
    { key: 'created_at', label: 'Date' },
];

const formattedEvents = computed(() => {
    return props.events.data.map((item) => {
        return {
            ...item,
            event_type: item.event_type.replace(/([A-Z])/g, ' $1').trim(), // Format enum for display
            user_id: item.user_id || 'N/A',
            payload: item.payload ? JSON.stringify(item.payload, null, 2) : 'N/A', // Pretty print JSON
            created_at: formatDate(item.created_at, { locale: 'fr', mode: 'relative' }),
        };
    });
});

</script>

<template>
    <AppLayout title="Mes Événements">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Journal des événements
            </h2>
        </template>

        <Container title="Mes Événements">
            <DataTable :headers="headers" :items="formattedEvents" :links="events.links">
                <template #item.payload="{ value }">
                    <pre class="text-xs max-h-24 overflow-y-auto bg-gray-100 dark:bg-gray-700 p-2 rounded">{{ value }}</pre>
                </template>
            </DataTable>
        </Container>
    </AppLayout>
</template>