<script setup lang="ts">
import ErrorMessage from './ErrorMessage.vue';
import HintMessage from './HintMessage.vue';

defineProps<{
    label: string;
    name: string;
    type: string;
    error?: string;
    required?: boolean;
    value?: string;
    hint?: string;
    disabled?: boolean;
    inline?: boolean;
    hiddenLabel?: boolean;
}>();

const model = defineModel();

const handleFile = (e: Event) => {
    const target = e.target as HTMLInputElement; // Assert the target as HTMLInputElement
    if (!target || !target.files) return;

    model.value = target.files[0];
};
</script>

<template>
    <div class="relative flex w-full flex-col">
        <label :for="name" class="pl-1.5 font-medium text-md" :class="[inline ? 'pr-1.5 w-1/6' : 'pb-1.5', hiddenLabel ? 'sr-only' : '']">
            {{ label }}
        </label>

        <input
            :type="type"
            :id="name"
            :name="name"
            :required="required"
            :disabled="disabled"
            class="inset-0 absolute cursor-pointer opacity-0"
            :class="disabled ? 'bg-gray-100 opacity-50' : ''"
            @input="handleFile"
        />
        <Action tag="button" variant="outline"> Upload File </Action>

        <HintMessage :hint="hint" />
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped>
input::file-selector-button {
    display: inline-flex;
    padding: 0.875rem;
    justify-content: center;
    align-items: center;
    gap: 0.625rem;
    cursor: pointer;

    border-radius: 0.4375rem;

    font-weight: 600;
    background: var(--c-primary);
    color: var(--c-white);
}
</style>
