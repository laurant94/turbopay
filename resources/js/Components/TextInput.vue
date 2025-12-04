<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    error: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        :class="[
            'block w-full px-3 py-2 border rounded-md sm:text-sm bg-white text-gray-900 ',
            error
                ? 'border-red-500 text-red-900  placeholder-red-300 focus:ring-red-500 focus:border-red-500'
                : 'border-gray-300 placeholder-gray-400  focus:ring-primary-500 focus:border-primary-500',
        ]"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
