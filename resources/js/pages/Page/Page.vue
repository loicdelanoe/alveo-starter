<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Menu } from '@/types/models/menu';
import { Page } from '@/types/models/page';

defineProps<{
    page: Page;
    menus: Menu[];
}>();
</script>

<template>
    <ClientLayout :page="page" :menus="menus" />
</template>

<style scoped></style>
