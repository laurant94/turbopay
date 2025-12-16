<script setup>

const props = defineProps({
    item: Object
})

</script>

<template>
<AppLayout title="Détails">
    <Container :title="item.fullname" :elevation="1" >
        <template #actions>
            sal
        </template>
    </Container>
</AppLayout>
</template>