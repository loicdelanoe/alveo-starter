<script setup lang="ts">
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
}>();

const model = defineModel();
</script>

<template>
    <div class="flex items-center gap-2" :class="{ 'flex-row-reverse': reverse, 'flex-col-reverse items-start justify-end': col }">
        <div class="relative mt-1">
            <input
                type="checkbox"
                :id="name"
                :name="name"
                :required="required"
                v-model="model"
                :value="value"
                :disabled="disabled"
                class="bg-secondary-100 peer checked:bg-accent-300 border-secondary-200 focus:ring-secondary-100 focus:ring-opacity-50 peer h-6 w-11 shrink-0 cursor-pointer appearance-none rounded-full border transition focus:ring-2 focus:outline disabled:cursor-not-allowed disabled:opacity-50"
            />
            <span
                class="pointer-events-none absolute top-0.5 left-0.5 inline-block h-5 w-5 cursor-pointer rounded-full bg-white shadow transition-transform duration-200 ease-in-out peer-checked:translate-x-5"
            ></span>
        </div>
        <label :for="name" class="w-full font-medium" :class="{ 'sr-only': hiddenLabel }">
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
