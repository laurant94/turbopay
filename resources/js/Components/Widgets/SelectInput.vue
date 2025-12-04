<script setup>
import { trans } from 'matice';
import { computed } from 'vue';

defineEmits(['update:modelValue']);

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: () => trans('widgets.select_input.default_placeholder'),
    }
});

</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ label }}</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <select
                :value="modelValue"
                @change="$emit('update:modelValue', $event.target.value)"
                :class="[
                    'block w-full px-3 py-2 border rounded-md sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200',
                    error
                        ? 'border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 dark:border-gray-600 focus:ring-primary-500 focus:border-primary-500',
                ]"
            >
                <option value="" disabled>{{ placeholder }}</option>
                <option v-for="option in options" :key="option.value || option" :value="option.value || option">
                    {{ option.label || option }}
                </option>
            </select>
        </div>
        <p v-if="error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ error }}</p>
    </div>
</template>
