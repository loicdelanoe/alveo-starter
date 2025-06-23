<script setup lang="ts">
import { Menu } from '@/types/models/menu';
import { Page } from '@/types/models/page';
import { getBlockCpt } from '@/utils/mapping';
import { Head, usePage } from '@inertiajs/vue3';

defineProps<{
    page: Page;
    menus: Menu[];
}>();

const pageProps = usePage().props;
</script>

<template>
    <Head :title="page.meta_title">
        <meta name="description" :content="page.meta_description" />
        <meta property="og:title" :content="page.meta_title" />
        <meta property="og:description" :content="page.meta_description" />
        <meta property="og:type" :content="page.og_type" />
        <meta property="og:url" :content="pageProps.ziggy.location" />
    </Head>

    <h1 class="sr-only">{{ page.title }}</h1>

    <pre>{{ page }}</pre>

    <main class="flex flex-col">
        <Suspense>
            <component
                v-for="block in page.blocks"
                :key="block.id"
                :is="getBlockCpt(block.block_type.type)"
                :content="block.content"
                :collections="page.collections"
            />
        </Suspense>
    </main>
    <slot />
</template>

<style scoped></style>
