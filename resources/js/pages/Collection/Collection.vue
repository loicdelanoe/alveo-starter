<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

import { Menu } from '@/types/models/menu';
import { getCollectionPage } from '@/utils/mapping';

defineProps<{
    // TODO: Type this
    collectionPage: any;
    menus: Menu[];
}>();

const pageProps = usePage().props;
</script>

<template>
    <Head :title="collectionPage.meta_title">
        <meta name="description" :content="collectionPage.meta_description" />
        <meta property="og:title" :content="collectionPage.meta_title" />
        <meta property="og:description" :content="collectionPage.meta_description" />
        <meta property="og:type" :content="collectionPage.og_type" />
        <meta property="og:url" :content="pageProps.ziggy.location" />
    </Head>

    <h1 class="sr-only">{{ collectionPage.title }}</h1>

    <main>
        <component :is="getCollectionPage(collectionPage.collection_type.type)" :collection="collectionPage" />
    </main>
</template>

<style scoped></style>
