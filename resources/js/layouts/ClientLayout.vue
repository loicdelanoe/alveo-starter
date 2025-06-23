<script setup lang="ts">
import { getBlockCpt } from '@/alveo/blockRegistry';
import { Menu } from '@/types/models/menu';
import { Page } from '@/types/models/page';
import { Head, usePage } from '@inertiajs/vue3';
import type { Component } from 'vue';
import { computed, defineAsyncComponent } from 'vue';

const props = defineProps<{
    page: Page;
    menus: Menu[];
}>();

const pageProps = usePage().props;

const title = computed(() => `${props.page.title} - ${pageProps.settings.site_name}`);
</script>

<template>
    <Head :title="title ?? page.meta_title">
        <meta name="description" :content="pageProps.settings.site_description ?? page.meta_description" />
        <meta property="og:title" :content="title ?? page.meta_title" />
        <meta property="og:description" :content="pageProps.settings.site_description ?? page.meta_description" />
        <meta property="og:type" :content="page.og_type" />
        <meta property="og:url" :content="pageProps.ziggy.location" />
        <link rel="icon" :href="pageProps.settings.favicon" type="image/svg+xml" />
    </Head>

    <h1 class="sr-only">{{ title }}</h1>

    <main class="flex flex-col">
        <Suspense>
            <component
                v-for="block in page.blocks"
                :key="block.id"
                :is="defineAsyncComponent(() => getBlockCpt(block.block_type.type) as Promise<Component>)"
                :content="block.content"
                :collections="page.collections"
            />
        </Suspense>
    </main>
    <slot />
</template>

<style scoped></style>
