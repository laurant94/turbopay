<script setup>
import { computed, ref } from 'vue';

const props = defineProps(['modelValue', 'options']);

const emit = defineEmits(['update:modelValue']);

const select = ref(null);

const proxySelected = computed({
    get() {
        return props.modelValue;
    },

    set(val) {
        emit('update:modelValue', val);
    },
});

</script>

<template>
    <select
        ref="select"
        v-model="proxySelected"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>
