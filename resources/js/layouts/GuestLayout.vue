<script setup lang="ts">
import Alveo from '@/components/admin/Icon/Alveo.vue';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <div class="bg-gray-100 flex min-h-screen items-center">
        <div class="w-0 h-0 md:w-1/2 md:h-screen">
            <div class="bg-accent-400 flex h-full w-full items-center justify-center">
                <Alveo class="w-1/3" />
            </div>
        </div>
        <div class="md:w-1/2 md:max-w-[480px] px-4 relative mx-auto flex max-h-screen min-h-screen w-full flex-col justify-center overflow-auto">
            <slot name="header" />
            <h1 class="text-3xl font-medium mb-10">{{ title }}</h1>
            <slot />
        </div>
    </div>
</template>
