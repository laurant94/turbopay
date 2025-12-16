<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    href: {
        required: false,
        type: String
    },
    variant: {
        type: String,
        default: 'primary', // primary, secondary, danger, success, warning
    },
    dense: {
        type: Boolean,
        default: false
    }
});

// Couleurs en fonction du variant
const variantClasses = computed(() => {
    const base = {
        primary: "bg-primary-600 hover:bg-primary-700 focus:ring-primary-500",
        secondary: "bg-gray-500 hover:bg-gray-700 focus:ring-gray-500",
        danger: "bg-red-600 hover:bg-red-700 focus:ring-red-500",
        success: "bg-green-600 hover:bg-green-700 focus:ring-green-500",
        warning: "bg-yellow-500 hover:bg-yellow-600 text-black focus:ring-yellow-400",
    };

    return base[props.variant] ?? base.primary;
});
</script>

<template>
    <component 
        :is="href ? Link : 'button'"
        :type="href ? null : type"
        :disabled="disabled"
        class="flex justify-center border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2"
        :class="[variantClasses, { 'opacity-50 cursor-not-allowed': disabled, 'py-3 px-4': !dense, 'py-2 px-2': dense }]"
    >
        <slot />
    </component>
</template>
