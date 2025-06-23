<script setup lang="ts">
defineProps<{
    variant: ('primary' | 'secondary' | 'outline' | 'icon' | 'delete')[] | 'primary' | 'secondary' | 'outline' | 'icon' | 'delete';
    size?: 'small' | 'normal' | 'large';
    href?: string;
    type?: 'button';
    tag: 'Link' | 'button';
    disabled?: boolean;
}>();
</script>

<template>
    <component
        :is="tag"
        :href="href"
        :class="[
            ...(Array.isArray(variant) ? variant : [variant]),
            size,
            { 'cursor-not-allowed opacity-60': disabled },
            { 'cursor-pointer': !disabled },
        ]"
        class="flex items-center justify-center gap-1 rounded-lg px-3 py-2.5 text-center text-sm font-medium transition"
    >
        <slot />
    </component>
</template>

<style scoped>
.small {
    padding: 0.4rem 0.88rem;
}
</style>
