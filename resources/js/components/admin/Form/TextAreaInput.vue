<script setup lang="ts">
import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';
import HintMessage from './HintMessage.vue';

defineProps<{
    label: string;
    name: string;
    error?: string;
    required?: boolean;
    hint?: string;
}>();

const model = defineModel<string>();
</script>

<template>
    <div class="flex flex-col">
        <label :for="name" class="text-md pl-1.5 font-medium pb-1.5">{{ label }}</label>
        <textarea
            class="border-secondary-300 min-h-42 rounded-lg px-3 py-2.5 border-1"
            :name="name"
            :id="name"
            v-model="model"
            :required="required"
        ></textarea>
        <HintMessage :hint="hint" />
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped></style>
