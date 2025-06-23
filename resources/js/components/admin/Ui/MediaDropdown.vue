<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const isDragging = ref(false);
const form = useForm({
    files: null,
});

function handleDrop(event) {
    isDragging.value = false;

    const droppedFiles = event.dataTransfer.files;

    if (droppedFiles.length) {
        form.files = droppedFiles;

        form.post(route('admin.media.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('files');
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
        <div class="space-y-2 text-center">
            <p class="font-medium text-secondary-950">Drag files here</p>
            <p class="text-sm text-secondary-500">or click to choose</p>
            <input type="file" multiple class="inset-0 absolute cursor-pointer opacity-0" @change="handleChange" />
        </div>
    </div>
</template>
