<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue';
import IconClose from '../Icon/IconClose.vue';

const props = defineProps<{
    title: string;
    subtitle?: string;
    size: 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | 'full';
    position: 'left' | 'center';
}>();

const isOpen = defineModel('isOpen', {
    default: false,
});
const emit = defineEmits(['closeModal']);

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
        isOpen.value = false;
        // emit('closeModal');
    }
};

const close = () => {
    isOpen.value = false;
    emit('closeModal');
};

onMounted(() => document.addEventListener('keydown', handleEscape));
onUnmounted(() => document.removeEventListener('keydown', handleEscape));
</script>

<template>
    <Transition name="slide" :duration="300">
        <Teleport to="body">
            <div v-if="isOpen" class="inset-0 bg-black/50 fixed z-40 flex items-center justify-center" @click.self="close">
                <div
                    class="modal m-3 gap-8 rounded-lg bg-white px-6 py-10 absolute z-20 flex w-full flex-col overflow-auto"
                    :class="[modalClass, modalPosition]"
                >
                    <div class="flex w-full items-center justify-between">
                        <div class="flex flex-col">
                            <h2 class="text-2xl font-medium">{{ title }}</h2>
                            <p v-if="subtitle" class="italic opacity-50">{{ subtitle }}</p>
                        </div>
                        <div class="gap-4 flex">
                            <slot name="action" />
                            <Action tag="button" variant="icon" @click="close">
                                <IconClose />
                            </Action>
                        </div>
                    </div>
                    <div class="px-1 py-1 overflow-auto">
                        <slot />
                    </div>
                </div>
            </div>
        </Teleport>
    </Transition>
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
