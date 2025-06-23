<script setup lang="ts">
import IconGrip from '@/components/admin/Icon/IconGrip.vue';

const props = defineProps<{
    index: number;
    posX?: number;
    posY?: number;
}>();

// model must be a reactive array
const model = defineModel<any[]>();

// dragged index must be a reactive number
const draggedIndex = defineModel<number | null>('dragged', { default: null });

const dragStart = (index: number, event: DragEvent) => {
    draggedIndex.value = index;

    // Get the parent node of the current target
    const nodeElement = (event.currentTarget as HTMLElement).parentNode as HTMLElement;

    // Set it as the drag image
    if (event.dataTransfer && nodeElement) {
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setDragImage(nodeElement, props.posX ?? 0, props.posY ?? 0);
    }
};

const drop = (index: number) => {
    if (draggedIndex.value !== null && model.value) {
        const temp = model.value[draggedIndex.value];

        model.value.splice(draggedIndex.value, 1);
        model.value.splice(index, 0, temp);

        draggedIndex.value = null;
    }
};
</script>

<template>
    <div class="border-secondary-200 flex items-center gap-2 rounded-lg border bg-white p-3.5 transition" @dragover.prevent @drop="drop(index)">
        <div draggable="true" @dragstart="(e) => dragStart(index, e)" class="cursor-move">
            <IconGrip />
        </div>
        <slot />
    </div>
</template>

<style scoped></style>
