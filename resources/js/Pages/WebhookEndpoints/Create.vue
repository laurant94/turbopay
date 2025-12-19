<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Widgets/Container.vue';
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { PlusIcon, MinusIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue'; // Import ref and watch

const props = defineProps({
    eventTypes: Array, // Array of { value: string, label: string }
});

const form = useForm({
    url: '',
    secret: '',
    events: [],
    headers: [{ key: '', value: '' }], // Initialize with one empty header for easy start
    active: true,
    subscribeToAllEvents: false, // New property
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

const createWebhook = () => {
    const filteredHeaders = form.headers.filter(header => header.key !== '' || header.value !== '');
    
    form.transform((data) => ({
        ...data,
        headers: filteredHeaders,
        // If subscribing to all events, send null, otherwise send the selected events
        events: data.subscribeToAllEvents ? null : data.events,
    })).post(route('user.webhook-endpoints.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.headers = [{ key: '', value: '' }];
            form.subscribeToAllEvents = false; // Reset the new property
        },
    });
};
</script>

<template>
    <AppLayout title="Créer un Webhook">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Créer un nouveau Webhook
            </h2>
        </template>

        <Container title="Détails du Webhook">
            <FormSection @submitted="createWebhook">
                <template #title>
                    Informations sur le Webhook
                </template>
                <template #description>
                    Configurez l'URL et les événements qui déclencheront ce webhook.
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
                            <Checkbox v-model:checked="form.subscribeToAllEvents" id="subscribeToAllEvents" />
                            <label for="subscribeToAllEvents" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
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
                        Créer le Webhook
                    </PrimaryButton>
                </template>
            </FormSection>
        </Container>
    </AppLayout>
</template>
