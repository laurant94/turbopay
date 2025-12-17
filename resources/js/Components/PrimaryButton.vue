<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    href: {
        required: false,
        type: String
    },
    dense: {
        type: Boolean,
        default: false
    },
    variant: {
        type: String,
        default: 'primary' // primary, secondary, danger, success, warning
    }
});

const variants = {
    primary:  'bg-blue-600 hover:bg-blue-700 border-transparent text-white',
    secondary:'bg-gray-200 hover:bg-gray-300 border-gray-300 text-gray-800',
    danger:   'bg-red-600 hover:bg-red-700 border-transparent text-white',
    success:  'bg-green-600 hover:bg-green-700 border-transparent text-white',
    warning:  'bg-yellow-500 hover:bg-yellow-600 border-transparent text-white'
};

</script>

<template>
    <component :is="href ? Link : 'button'" 
        :type="href ? null : type"
        :class="[
            dense ? 'px-2 py-2' : 'px-4 py-2',
            'disabled:opacity-5',
            'inline-flex items-center rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring transition',
            variants[variant] ?? variants.primary,
        ]"
    >
        <slot />
    </component>
</template>
