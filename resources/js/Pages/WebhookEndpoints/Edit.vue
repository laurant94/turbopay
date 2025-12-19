<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue'; // Added for Add/Remove buttons
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { PlusIcon, MinusIcon } from 'lucide-vue-next'; // Added for icons
import { ref, watch } from 'vue'; // Import ref and watch

const props = defineProps({
    webhookEndpoint: Object, // The existing webhook endpoint data
    eventTypes: Array,      // Array of { value: string, label: string }
});

const form = useForm({
    _method: 'PUT', // Important for Inertia to send a PUT request
    url: props.webhookEndpoint.url,
    secret: props.webhookEndpoint.secret,
    events: props.webhookEndpoint.events || [], // Pre-fill with existing events
    // Initialize headers from existing data, or with one empty field if none
    headers: props.webhookEndpoint.headers && props.webhookEndpoint.headers.length > 0
             ? props.webhookEndpoint.headers
             : [{ key: '', value: '' }],
    active: props.webhookEndpoint.active,
    // Initialize subscribeToAllEvents based on whether events is null
    subscribeToAllEvents: props.webhookEndpoint.events === null,
});

// Watch for changes in subscribeToAllEvents
watch(() => form.subscribeToAllEvents, (newValue) => {
    if (newValue) {
        form.events = []; // Clear specific events when subscribing to all
    }
});

const addHeader = () => {
    form.headers.push({ key: '', value: '' });
};

const removeHeader = (index) => {
    form.headers.splice(index, 1);
};

const updateWebhook = () => {
    // Filter out empty headers before submitting
    const filteredHeaders = form.headers.filter(header => header.key !== '' || header.value !== '');

    form.transform((data) => ({
        ...data,
        headers: filteredHeaders,
        // If subscribing to all events, send null, otherwise send the selected events
        events: data.subscribeToAllEvents ? null : data.events,
    })).post(route('user.webhook-endpoints.update', props.webhookEndpoint.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Optionally, show a success message or redirect
        },
    });
};
</script>

<template>
    <AppLayout title="Modifier le Webhook">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Modifier le Webhook
            </h2>
        </template>

        <Container title="Détails du Webhook">
            <FormSection @submitted="updateWebhook">
                <template #title>
                    Informations sur le Webhook
                </template>
                <template #description>
                    Mettez à jour l'URL et les événements qui déclencheront ce webhook.
                </template>

                <template #form>
                    <!-- Active Status - Moved up -->
                    <div class="col-span-6 sm:col-span-4">
                        <label class="flex items-center space-x-2">
                            <Checkbox v-model:checked="form.active" />
                            <span class="text-sm text-gray-600 dark:text-gray-400">Activer le Webhook</span>
                        </label>
                        <InputError :message="form.errors.active" class="mt-2" />
                    </div>

                    <!-- URL -->
                    <div class="col-span-6">
                        <InputLabel for="url" value="URL du Webhook" />
                        <TextInput
                            id="url"
                            v-model="form.url"
                            type="url"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                        <InputError :message="form.errors.url" class="mt-2" />
                    </div>

                    <!-- Secret (Optional) -->
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel for="secret" value="Secret (Optionnel)" />
                        <TextInput
                            id="secret"
                            v-model="form.secret"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Laisser vide pour générer automatiquement"
                        />
                        <InputError :message="form.errors.secret" class="mt-2" />
                    </div>

                    <!-- Headers (Optional) -->
                    <div class="col-span-6">
                        <InputLabel value="Headers personnalisés (Optionnel)" class="mb-2" />
                        <div v-for="(header, index) in form.headers" :key="index" class="flex items-center space-x-2 mb-2">
                            <TextInput
                                v-model="header.key"
                                :id="`header-key-${index}`"
                                type="text"
                                class="flex-1"
                                placeholder="Nom du Header (ex: X-Custom-Header)"
                            />
                            <TextInput
                                v-model="header.value"
                                :id="`header-value-${index}`"
                                type="text"
                                class="flex-1"
                                placeholder="Valeur du Header"
                            />
                            <SecondaryButton type="button" @click="removeHeader(index)" v-if="form.headers.length > 1 || (header.key !== '' || header.value !== '')">
                                <MinusIcon class="w-4 h-4" />
                            </SecondaryButton>
                        </div>
                        <SecondaryButton type="button" @click="addHeader" class="mt-2">
                            <PlusIcon class="w-4 h-4 mr-2" /> Ajouter un Header
                        </SecondaryButton>
                        <InputError :message="form.errors[`headers.${index}.key`]" class="mt-2" v-for="(header, index) in form.headers" :key="`header-key-error-${index}`" />
                        <InputError :message="form.errors[`headers.${index}.value`]" class="mt-2" v-for="(header, index) in form.headers" :key="`header-value-error-${index}`" />
                    </div>

                    <!-- Events Selection -->
                    <div class="col-span-6">
                        <InputLabel value="Événements déclencheurs" class="mb-2" />
                        <div class="flex items-center mb-4">
                            <Checkbox v-model:checked="form.subscribeToAllEvents" id="subscribeToAllEventsEdit" />
                            <label for="subscribeToAllEventsEdit" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                S'abonner à tous les événements
                            </label>
                        </div>
                        <div :class="{ 'opacity-50 cursor-not-allowed': form.subscribeToAllEvents }">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="eventType in eventTypes" :key="eventType.value">
                                    <label class="flex items-center">
                                        <Checkbox v-model:checked="form.events" :value="eventType.value" :disabled="form.subscribeToAllEvents" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ eventType.label }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <InputError :message="form.errors.events" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <Link :href="route('user.webhook-endpoints.index')" class="mr-4 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        Annuler
                    </Link>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Mettre à jour le Webhook
                    </PrimaryButton>
                </template>
            </FormSection>
        </Container>
    </AppLayout>
</template>
