<script setup lang="ts">
import IconSquareArrowRight from '@/components/admin/Icon/IconSquareArrowRight.vue';

defineProps<{
    href: string;
    isHome?: boolean;
}>();
</script>

<template>
    <a :href="isHome ? '/' : href" target="_blank" class="text-secondary-500 hover:text-secondary-800 flex items-center gap-2 underline transition">
        Visit page
        <IconSquareArrowRight />
    </a>
</template>

<style scoped></style>
