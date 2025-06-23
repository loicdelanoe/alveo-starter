<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue';

import IconClose from '@/components/admin/Icon/IconClose.vue';
import { getIconCpt } from '@/utils/mapping';

const props = defineProps<{
    label: string;
    title: string;
    variant: 'primary' | 'secondary' | 'outline';
    size: 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | 'full';
    position: 'left' | 'center';
    icon?: string;
    buttonSize?: 'small' | 'normal' | 'large';
}>();

const model = defineModel();

const modalClass = computed(() => {
    return {
        'sm:max-w-md': props.size === 'md',
        'sm:max-w-lg': props.size === 'lg',
        'sm:max-w-xl': props.size === 'xl',
        'sm:max-w-2xl': props.size === '2xl',
        'sm:max-w-3xl': props.size === '3xl',
        'sm:max-w-6xl': props.size === '4xl',
    };
});

const modalPosition = computed(() => {
    return {
        'right-0 top-0 bottom-0 side-left overflow-auto': props.position === 'left',
        'top-1/2 right-0 left-1/2 translate-x-[-50%] translate-y-[-50%] min-h-[50vh] max-h-[90vh] overflow-hidden': props.position === 'center',
    };
});

const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
        model.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', handleEscape));
onUnmounted(() => document.removeEventListener('keydown', handleEscape));
</script>

<template>
    <div>
        <Action tag="button" :variant="variant" @click.prevent="model = !model" :size="buttonSize">
            <component v-if="icon" :is="getIconCpt(icon)" />
            {{ label }}
        </Action>

        <Transition name="slide" :duration="300">
            <Teleport to="body">
                <div v-if="model" class="fixed inset-0 z-40 flex items-center justify-center bg-black/50" @click.self="model = false">
                    <div
                        class="modal absolute z-20 m-3 flex w-full flex-col gap-8 overflow-auto rounded-lg bg-white px-6 py-10"
                        :class="[modalClass, modalPosition]"
                    >
                        <div class="flex w-full items-center justify-between">
                            <h2 class="text-2xl font-medium">{{ title }}</h2>
                            <div class="flex gap-4">
                                <slot name="action" />
                                <Action tag="button" class="" variant="icon" @click="model = false">
                                    <IconClose />
                                </Action>
                            </div>
                        </div>
                        <div class="overflow-auto px-1 py-1">
                            <slot />
                        </div>
                        <slot name="footer" />
                    </div>
                </div>
            </Teleport>
        </Transition>
    </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: opacity 0.3s ease-out;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
}

.slide-enter-active .modal,
.slide-leave-active .modal {
    transition: transform 0.3s ease-out;
}

.slide-enter-from .modal,
.slide-leave-to .modal {
    transform: translateX(100%);
}

.slide-enter-from .modal:not(.side-left),
.slide-leave-to .modal:not(.side-left) {
    transform: none;
}
</style>
