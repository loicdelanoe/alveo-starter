<script setup lang="ts">
import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';

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
    <div class="gap-1 flex flex-col">
        <label :for="name" class="text-md pl-1.5 font-medium">{{ label }}</label>
        <textarea
            class="border-secondary-300 min-h-42 rounded-lg px-3 py-2.5 border-1"
            :name="name"
            :id="name"
            v-model="model"
            :required="required"
        ></textarea>
        <span v-if="hint" class="text-sm italic opacity-50">{{ hint }}</span>
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped></style>
