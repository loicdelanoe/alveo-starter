<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Menu } from '@/types/models/menu';
import { Page } from '@/types/models/page';
import type { Component } from 'vue';

import { getBlockCpt } from '@/alveo/blockRegistry';
import { defineAsyncComponent } from 'vue';

defineProps<{
    page: Page;
    menus: Menu[];
}>();
</script>

<template>
    <ClientLayout :page="page" :menus="menus">
        <Suspense>
            <component
                v-for="block in page.blocks"
                :key="block.id"
                :is="defineAsyncComponent(() => getBlockCpt(block.block_type.type) as Promise<Component>)"
                :content="block.content"
                :collections="page.collections"
                :forms="page.forms"
            />
        </Suspense>
    </ClientLayout>
</template>

<style scoped></style>
