<script setup lang="ts">
import { computed, ref } from 'vue';

import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';
import IconEye from '@/components/admin/Icon/IconEye.vue';
import IconEyeOff from '@/components/admin/Icon/IconEyeOff.vue';
import HintMessage from './HintMessage.vue';

defineProps<{
    label: string;
    name: string;
    type?: 'password' | 'text' | 'search' | 'email' | 'number';
    error?: string;
    required?: boolean;
    value?: string | number;
    hint?: string;
    disabled?: boolean;
    placeholder?: string;
    inline?: boolean;
}>();

const model = defineModel();

const isToggle = ref(false);

const calculatedType = computed(() => {
    return isToggle.value ? 'text' : 'password';
});
</script>

<template>
    <div class="flex w-full flex-col">
        <div class="flex" :class="inline ? 'flex-row items-center' : 'flex-col'">
            <label :for="name" class="pl-1.5 font-medium text-md" :class="inline ? 'pr-1.5 w-1/6' : 'pb-1.5'">{{ label }}</label>
            <div class="relative w-full">
                <input
                    :type="type === 'password' ? calculatedType : type"
                    :id="name"
                    :name="name"
                    :required="required"
                    :placeholder="placeholder"
                    v-model="model"
                    :disabled="disabled"
                    :class="[disabled ? 'bg-gray-100 opacity-50' : '', inline ?? 'flex-1']"
                    class="px-3 py-1.5 border-secondary-300 rounded-lg relative w-full border-1"
                />
                <div
                    v-if="type === 'password'"
                    class="right-3 absolute top-1/2 -translate-y-1/2 cursor-pointer"
                    @click.prevent="isToggle = !isToggle"
                >
                    <IconEye width="5" v-if="!isToggle" />
                    <IconEyeOff width="5" v-else />
                </div>
            </div>
        </div>

        <HintMessage :hint="hint" />
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped></style>
