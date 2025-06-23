<script setup lang="ts">
defineProps<{
    hint?: string;
}>();
</script>

<template>
    <span v-if="hint" class="text-sm italic opacity-50">{{ hint }}</span>
</template>

<style scoped></style>
