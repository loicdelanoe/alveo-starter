<script setup lang="ts">
import IconGrip from '@/components/admin/Icon/IconGrip.vue';

const props = defineProps<{
    array: any[];
    index: number;
    posX?: number;
    posY?: number;
}>();

// model must be a reactive array
const emit = defineEmits<{
    (e: 'elementDrop'): void;
}>();

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

const drop = (targetIndex: number) => {
    if (draggedIndex.value !== null && draggedIndex.value !== targetIndex) {
        const moved = props.array[draggedIndex.value];
        props.array.splice(draggedIndex.value, 1);
        props.array.splice(targetIndex, 0, moved);

        emit('elementDrop');
    }

    draggedIndex.value = null;
};

const dragEnd = () => {
    draggedIndex.value = null;
};
</script>

<template>
    <div
        class="border-secondary-200 gap-2 rounded-lg bg-white p-3.5 flex items-center border transition"
        @dragover.prevent
        @drop="drop(index)"
        v-bind="$attrs"
    >
        <div draggable="true" @dragstart="(e) => dragStart(index, e)" @dragend="dragEnd" class="cursor-move">
            <IconGrip />
        </div>
        <slot />
    </div>
</template>

<style scoped></style>
