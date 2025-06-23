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
    transparent?: boolean;
}>();

const model = defineModel();
</script>

<template>
    <div class="z-2 flex items-center" :class="{ 'flex-row-reverse': reverse, 'flex-col-reverse items-start justify-end': col }" v-bind="$attrs">
        <div class="mt-1 relative">
            <input
                type="checkbox"
                :id="name"
                :name="name"
                :required="required"
                v-model="model"
                :value="value"
                :disabled="disabled"
                class="border-secondary-200 checked:bg-accent-300 focus:ring-secondary-200 focus:ring-opacity-50 peer mt-1 h-4 w-4 rounded-sm shrink-0 cursor-pointer appearance-none border transition focus:ring-2 focus:outline disabled:cursor-not-allowed disabled:opacity-50"
                :class="transparent ? 'bg-transparent' : 'bg-white'"
            />
            <IconCheck class="text-secondary-950 inset-0 mt-1 !h-4 !w-4 pointer-events-none absolute hidden peer-checked:block" />
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
