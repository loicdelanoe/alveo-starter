<script setup lang="ts">
import IconLayout from '@/components/admin/Icon/IconLayout.vue';

defineProps<{
    width?: string;
}>();
</script>

<template>
    <IconLayout :width="width">
        <path d="m20 13.7-2.1-2.1a2 2 0 0 0-2.8 0L9.7 17" />
        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
        <circle cx="10" cy="8" r="2" />
    </IconLayout>
</template>

<style scoped></style>
