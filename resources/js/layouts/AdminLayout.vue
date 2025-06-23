<script setup lang="ts">
// import '@Styles/admin/admin.css';

import SideNavigation from '@/components/admin/Navigation/SideNavigation.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <Head :title="`Admin - ${page.props.settings.site_name}`"> </Head>
    <div class="layout flex bg-[#f5f5f5]">
        <h1 class="sr-only">Alveo</h1>

        <Suspense>
            <SideNavigation />
        </Suspense>

        <slot />
    </div>
</template>

<style scoped></style>
