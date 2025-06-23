<script setup lang="ts">
import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';

defineProps<{
    label: string;
    name: string;
    error?: string;
    required?: boolean;
}>();

const model = defineModel<string>();
</script>

<template>
    <div class="flex flex-col gap-1">
        <label :for="name" class="text-md pl-1.5 font-medium">{{ label }}</label>
        <textarea
            class="border-secondary-300 min-h-42 rounded-lg border-1 px-3 py-2.5"
            :name="name"
            :id="name"
            v-model="model"
            :required="required"
        ></textarea>
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped></style>
