<script setup>
    const props = defineProps({
        error: {
            type: String,
        }
    })
</script>

<template>
    <i v-if="error" v-text="error" class="text-red-900 text-xs" ></i>
</template>