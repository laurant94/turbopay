<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import DataTable from '@/Components/Widgets/DataTable.vue';
import { computed } from 'vue';
import { trans } from 'matice';

const props = defineProps({
    logs: Object,
});

const headers = [
    { key: 'event', label: 'Événement' },
    { key: 'auditable_type', label: 'Type auditée' },
    { key: 'auditable_id', label: 'ID auditée' },
    { key: 'user_type', label: 'Type utilisateur' },
    { key: 'user_id', label: 'ID Utilisateur' },
    { key: 'ip', label: 'IP' },
    { key: 'old_values', label: 'Anciennes valeurs' },
    { key: 'new_values', label: 'Nouvelles valeurs' },
];

const formattedLogs = computed(() => {
    return props.logs.data.map((item) => {
        return {
            ...item,
            // You might want to format these for better display, e.g., JSON.stringify for objects
            old_values: item.old_values ? JSON.stringify(item.old_values) : '-',
            new_values: item.new_values ? JSON.stringify(item.new_values) : '-',
        };
    });
});

</script>

<template>
    <AppLayout title="Journal d'Audit">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Journal d'Audit
            </h2>
        </template>

        <Container title="Journal d'Audit">
            <DataTable :headers="headers" :items="formattedLogs" :links="logs.links" />
        </Container>
    </AppLayout>
</template>
