<template>
    <div v-if="links?.length > 3" class="mt-6 flex justify-center space-x-2">
        <template v-for="(link, key) in links">
            <Link
                v-if="link.url"
                :key="key"
                :href="link.url"
                v-html="link.label"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-300 dark:bg-gray-800"
                :class="{ 'bg-primary-500 dark:bg-primary-600 text-white': link.active, 'text-gray-700 dark:text-gray-200': !link.active }"
            />
        </template>
        
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>


 <!-- <div v-if="orders.links.length > 3" class="mt-4 flex justify-between items-center">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link :href="orders.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700" :class="{'opacity-50 cursor-not-allowed': !orders.prev_page_url}" :disabled="!orders.prev_page_url">Précédent</Link>
                            <Link :href="orders.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700" :class="{'opacity-50 cursor-not-allowed': !orders.next_page_url}" :disabled="!orders.next_page_url">Suivant</Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-400">
                                    Affiche
                                    <span class="font-medium">{{ orders.from }}</span>
                                    à
                                    <span class="font-medium">{{ orders.to }}</span>
                                    de
                                    <span class="font-medium">{{ orders.total }}</span>
                                    résultats
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <Link v-for="(link, k) in orders.links" :key="k" :href="link.url" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium" :class="{'z-10 bg-indigo-50 dark:bg-indigo-900/50 border-indigo-500 dark:border-indigo-700 text-indigo-600 dark:text-indigo-300': link.active, 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700': !link.active}" v-html="link.label" />
                                </nav>
                            </div>
                        </div>
                    </div> -->