<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import BadgeSuccess from '@/components/admin/Badge/BadgeSuccess.vue';

interface Flash {
    success?: string;
    error?: string;
    info?: string;
}

const flash = usePage().props.flash as Flash;

const showNotification = ref(false);
const currentMessage = ref<string | null>(null);
const currentType = ref<string | null>(null);

watch(
    () => flash,
    (newFlash) => {
        const type = newFlash.success ? 'success' : newFlash.error ? 'error' : newFlash.info ? 'info' : null;

        if (type) {
            currentMessage.value = newFlash[type] as string;
            currentType.value = type;
            showNotification.value = true;

            setTimeout(() => {
                showNotification.value = false;
                currentMessage.value = null;
                currentType.value = null;
            }, 2000);
        }
    },
    { immediate: true },
);
</script>

<template>
    <Transition>
        <span
            class="bg-secondary-950 bottom-9 right-5 gap-3 rounded-lg px-6 py-4 font-medium fixed z-10 flex border border-[#2AA383] text-[#2AA383]"
            v-if="showNotification"
        >
            <BadgeSuccess />
            {{ flash.success }}
        </span>
    </Transition>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
