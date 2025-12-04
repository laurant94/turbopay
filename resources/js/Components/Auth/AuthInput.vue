<script setup>
import { VTooltip } from 'floating-vue';

defineProps({
    label: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'text',
    },
    modelValue: {
        type: String,
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (event) => {
    emit('update:modelValue', event.target.value);
};
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <input
                :type="type"
                :value="modelValue"
                @input="updateValue"
                :class="[
                    'block w-full px-3 py-2 border rounded-md sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200',
                    error
                        ? 'border-red-500 text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-500 focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 dark:border-gray-600 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-primary-500 focus:border-primary-500',
                ]"
            />
            <i v-if="error" v-text="error" class="text-red-900 text-xs" ></i>
            <!-- <div v-if="error" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                 <VTooltip>
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <template #popper>
                        {{ error }}
                    </template>
                </VTooltip>
            </div> -->
        </div>
    </div>
</template>
