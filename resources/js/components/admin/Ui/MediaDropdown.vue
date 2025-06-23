<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ErrorMessage from '../Form/ErrorMessage.vue';

const isDragging = ref(false);

const form = useForm({
    files: null,
});

const emit = defineEmits(['closeModal']);

function handleDrop(event) {
    isDragging.value = false;

    const droppedFiles = event.dataTransfer.files;

    if (droppedFiles.length) {
        form.files = droppedFiles;

        form.post(route('admin.media.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('files');
                emit('closeModal');
            },
        });
    }
}

function handleChange(e) {
    form.files = e.target.files;

    form.post(route('admin.media.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('files');
            emit('closeModal');
        },
    });
}
</script>

<template>
    <div
        v-bind="$attrs"
        class="rounded-lg p-6 hover:bg-secondary-50 border-secondary-950 relative flex h-1/2 cursor-pointer items-center justify-center border-2 border-dashed transition"
        :class="{ 'border-blue-400 bg-blue-50': isDragging }"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop"
    >
        <div class="gap-2 flex flex-col items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="w-22 text-secondary-950"
            >
                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                <circle cx="10" cy="12" r="2" />
                <path d="m20 17-1.296-1.296a2.41 2.41 0 0 0-3.408 0L9 22" />
            </svg>
            <p class="font-medium text-secondary-950">Drag files here</p>
            <p class="text-sm text-secondary-500">or click to choose</p>
            <input type="file" multiple class="inset-0 absolute cursor-pointer opacity-0" @change="handleChange" />
            <template v-if="form.errors">
                <ErrorMessage v-for="(value, key) in form.errors" :key="key" :error="value" />
            </template>
        </div>
    </div>
</template>
