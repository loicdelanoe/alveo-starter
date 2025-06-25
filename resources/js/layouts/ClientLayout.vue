<script setup lang="ts">
// import MainNavigation from '@/components/partials/MainNavigation.vue';
import { Menu } from '@/types/models/menu';
import { Page } from '@/types/models/page';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    page: Page;
    menus: Menu[];
}>();

const pageProps = usePage().props;

const title = computed(() => `${props.page.title} - ${pageProps.settings.site_name}`);

// const mainNavigation = getMenu('main-navigation');
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

    <!-- <MainNavigation v-if="mainNavigation" :menu="mainNavigation" /> -->

    <main>
        <slot />
    </main>
</template>

<style scoped></style>
