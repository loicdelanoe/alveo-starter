<script setup lang="ts">
import { getCollectionCpt } from '@/alveo/collectionRegistry';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Menu } from '@/types/models/menu';
import type { Component } from 'vue';

import { defineAsyncComponent } from 'vue';

defineProps<{
    // TODO: Type this
    collectionPage: any;
    menus: Menu[];
}>();
</script>

<template>
    <ClientLayout :page="collectionPage" :menus="menus">
        <component
            :is="defineAsyncComponent(() => getCollectionCpt(collectionPage.collection_type.type) as Promise<Component>)"
            :collection="collectionPage"
        />
    </ClientLayout>
</template>

<style scoped></style>
