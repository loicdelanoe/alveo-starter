<script setup lang="ts">
import { InertiaForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import IconChevronDown from '@/components/admin/Icon/IconChevronDown.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconGrip from '@/components/admin/Icon/IconGrip.vue';
import Container from '@/components/admin/Ui/Container.vue';
import type { BlockType } from '@/pages/Admin/Block/Create.vue';
import type { PageForm } from '@/pages/Admin/Page/Show.vue';
import { getInputCpt } from '@/utils/mapping';

import type { Block } from '@/types/models/block';

const props = defineProps<{
    blockType: BlockType;
    block: Block;
    index: number;
    pageForm: InertiaForm<PageForm>;
}>();

const blockRef = ref<HTMLElement | null>(null);
const isToggle = ref(true);

const removeFromPage = () => {
    props.pageForm.blocks.splice(props.index, 1);
};

const draggedIndex = defineModel<number | null>('dragged', { default: null });

const dragStart = (index: number, event: DragEvent) => {
    draggedIndex.value = index;

    // Get the parent node of the current target
    const nodeElement = (event.currentTarget as HTMLElement).parentNode as HTMLElement;

    // Set it as the drag image
    if (event.dataTransfer && nodeElement) {
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setDragImage(nodeElement.parentNode, 0, 0);
    }
};

const drop = (index: number) => {
    if (draggedIndex.value !== null) {
        const temp = props.pageForm.blocks[draggedIndex.value];

        props.pageForm.blocks.splice(draggedIndex.value, 1);
        props.pageForm.blocks.splice(index, 0, temp);

        // Update order index
        props.pageForm.blocks.forEach((item, index) => {
            item.pivot.order = index;
        });

        draggedIndex.value = null;
    }
};
</script>

<template>
    <Container @dragover.prevent @drop="drop(index)" class="gap-4 flex flex-col" ref="blockRef" :aria-expanded="isToggle">
        <div class="gap-2 flex items-center">
            <div draggable="true" @dragstart="(e) => dragStart(index, e)" class="cursor-move">
                <IconGrip />
            </div>
            <div class="gap-2 flex items-center">
                <span>{{ index + 1 }}</span>
                <p class="text-xl font-medium">{{ blockType.name }}</p>
            </div>
            <div class="gap-2 ml-auto flex">
                <Action tag="button" variant="icon" @click="isToggle = !isToggle" :class="isToggle ? '' : 'rotate-90'">
                    <span class="sr-only">Toggle block</span>
                    <IconChevronDown />
                </Action>
                <Action @click="removeFromPage" tag="button" variant="icon">
                    <IconClose />
                </Action>
            </div>
        </div>

        <!-- Render fields -->
        <dl v-show="isToggle" class="gap-4 ease-in-out flex flex-col overflow-hidden transition-all duration-500">
            <template v-for="field in blockType.fields" :key="`block-${index}-${field.name}`">
                <component
                    :is="getInputCpt(field.type)"
                    :type="field.type"
                    :name="`block-${index}-${field.name}`"
                    :label="field.label"
                    v-model="pageForm.blocks[index].content[field.name]"
                    :repeater-fields="field.repeaterFields"
                    :error="pageForm.errors[`blocks.${index}.content.${field.name}`]"
                    :inline="field.type === 'repeater'"
                />
            </template>
        </dl>
    </Container>
</template>

<style scoped>
.v-enter-from {
    opacity: 0;
    rotate: 180deg;
}
.v-enter-to {
    opacity: 1;
    rotate: 0deg;
}
.v-enter-active {
    transition: all 0.7s;
}
</style>
