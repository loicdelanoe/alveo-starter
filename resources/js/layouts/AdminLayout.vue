<script setup lang="ts">
// import '@Styles/admin/admin.css';

import SideNavigation from '@/components/admin/Navigation/SideNavigation.vue';
</script>

<template>
    <div class="layout flex bg-[#f5f5f5]">
        <h1 class="sr-only">Alveo</h1>
        <Suspense>
            <SideNavigation />
        </Suspense>
        <slot />
    </div>
</template>

<style scoped></style>
