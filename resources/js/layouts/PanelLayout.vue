<script setup lang="ts">
import IconPanelLeft from '@/components/admin/Icon/IconPanelLeft.vue';
import NotificationBar from '@/components/admin/Ui/NotificationBar.vue';
import { useSidebarStore } from '@/stores/sidebar';

defineProps<{
    title: string;
    subtitle?: string;
}>();

const sidebar = useSidebarStore();
</script>

<template>
    <main class="relative min-h-screen w-full px-4 py-7 transition-all md:p-7" :class="{ 'md:ml-68': sidebar.isOpen }">
        <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex gap-2">
                <IconPanelLeft @click="sidebar.toggle" class="cursor-pointer self-center" />
                <div class="flex flex-col">
                    <slot name="breadcrumbs" />
                    <h2 class="text-2xl font-medium md:text-4xl">
                        {{ title }}
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-4 md:ml-auto">
                <slot name="action" />
            </div>
        </div>
        <NotificationBar />
        <Suspense>
            <slot />
        </Suspense>
    </main>
</template>

<style scoped>
.title {
    font-size: var(--fs-h1);
}
</style>
