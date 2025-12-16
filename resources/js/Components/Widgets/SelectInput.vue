<script setup>
defineEmits(['update:modelValue']);

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number, Object],
        required: false,
        default: null,
    },
    error: {
        type: String,
        default: '',
    },

    /**
     * options peut être :
     * - ['A', 'B', 'C']
     * - [{ id: 1, title: 'A' }, { id: 2, title: 'B' }]
     */
    options: {
        type: Array,
        required: true,
    },

    /**
     * Comme Vuetify : item-title et item-value
     */
    optionLabel: {
        type: String,
        default: 'label', // valeur par défaut si array d’objets
    },
    optionValue: {
        type: String,
        default: 'value',
    },

    placeholder: {
        type: String,
        default: () => "Selectionner une option",
    },
});

/**
 * Normalisation des options :
 * Si array simple => convertit en {label, value}
 * Si array d’objets => utilise optionLabel et optionValue
 */
const normalizedOptions = computed(() => {
    return props.options.map(opt => {
        if (typeof opt === 'string' || typeof opt === 'number') {
            return { label: opt, value: opt };
        }

        return {
            label: opt[props.optionLabel],
            value: opt[props.optionValue],
            raw: opt
        };
    });
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

                <option
                    v-for="opt in normalizedOptions"
                    :key="opt.value"
                    :value="opt.value"
                >
                    {{ opt.label }}
                </option>
            </select>
        </div>

        <p v-if="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>
