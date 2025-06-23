<script setup lang="ts">
import IconCheck from '@/components/admin/Icon/IconCheck.vue';

defineProps<{
    label: string;
    name: string;
    error?: string;
    required?: boolean;
    value?: string | number;
    hint?: string;
    disabled?: boolean;
    reverse?: boolean;
    hiddenLabel?: boolean;
    col?: boolean;
    smallLabel?: boolean;
}>();

const model = defineModel();
</script>

<template>
    <div class="relative z-2 flex items-center" :class="{ 'flex-row-reverse': reverse, 'flex-col-reverse items-start justify-end': col }">
        <div class="relative mt-1">
            <input
                type="checkbox"
                :id="name"
                :name="name"
                :required="required"
                v-model="model"
                :value="value"
                :disabled="disabled"
                class="border-secondary-200 checked:bg-accent-300 focus:ring-secondary-200 focus:ring-opacity-50 peer mt-1 h-4 w-4 shrink-0 cursor-pointer appearance-none rounded-sm border bg-white transition focus:ring-2 focus:outline disabled:cursor-not-allowed disabled:opacity-50"
            />
            <IconCheck class="text-secondary-950 pointer-events-none absolute inset-0 mt-1 hidden !h-4 !w-4 peer-checked:block" />
        </div>
        <label
            :for="name"
            class="w-full"
            :class="[{ 'sr-only': hiddenLabel }, smallLabel ? 'text-secondary-600 text-sm' : 'font-medium', col ? '' : 'pl-2']"
        >
            {{ label }}
        </label>
    </div>
</template>

<style scoped>
input:before {
    content: '';
    transform-origin: bottom left;
    clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
}
</style>
