<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { PencilIcon, ArrowLeftIcon } from 'lucide-vue-next';

const props = defineProps({
    webhookEndpoint: Object,
});

// Format events for display
const formattedEvents = props.webhookEndpoint.events === null
    ? 'Tous les événements'
    : (props.webhookEndpoint.events && props.webhookEndpoint.events.length > 0
        ? props.webhookEndpoint.events.map(event => event.replace(/([A-Z])/g, ' $1').trim()).join(', ')
        : 'Aucun événement spécifique');

// Format headers for display
const formattedHeaders = props.webhookEndpoint.headers && props.webhookEndpoint.headers.length > 0
    ? props.webhookEndpoint.headers
    : [];

</script>

<template>
    <AppLayout title="Détails du Webhook">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Détails du Webhook
            </h2>
        </template>

        <Container :title="`Webhook pour ${webhookEndpoint.url}`">
            <template #action>
                <Link :href="route('user.webhook-endpoints.index')">
                    <SecondaryButton class="mr-2">
                        <ArrowLeftIcon class="w-4 h-4 mr-1" /> Retour aux Webhooks
                    </SecondaryButton>
                </Link>
                <Link :href="route('user.webhook-endpoints.edit', webhookEndpoint.id)">
                    <PrimaryButton>
                        <PencilIcon class="w-4 h-4 mr-1" /> Modifier
                    </PrimaryButton>
                </Link>
            </template>

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations Générales</h3>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <p><strong>URL:</strong> {{ webhookEndpoint.url }}</p>
                        <p><strong>Secret:</strong> {{ webhookEndpoint.secret ? webhookEndpoint.secret: 'Non défini' }}</p>
                        <p><strong>Actif:</strong> <span :class="{'text-green-500': webhookEndpoint.active, 'text-red-500': !webhookEndpoint.active}">{{ webhookEndpoint.active ? 'Oui' : 'Non' }}</span></p>
                        <p><strong>Créé le:</strong> {{ new Date(webhookEndpoint.created_at).toLocaleString() }}</p>
                        <p><strong>Mis à jour le:</strong> {{ new Date(webhookEndpoint.updated_at).toLocaleString() }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Événements Abonnés</h3>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <p>{{ formattedEvents }}</p>
                    </div>
                </div>

                <div v-if="formattedHeaders.length > 0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Headers Personnalisés</h3>
                    <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li v-for="(header, index) in formattedHeaders" :key="index">
                            <strong>{{ header.key }}:</strong> {{ header.value }}
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Headers Personnalisés</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Aucun header personnalisé.</p>
                </div>
            </div>
        </Container>
    </AppLayout>
</template>
