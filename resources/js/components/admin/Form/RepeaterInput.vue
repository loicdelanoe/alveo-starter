<script setup lang="ts">
import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconGrip from '@/components/admin/Icon/IconGrip.vue';
import { getInputCpt } from '@/utils/mapping';

const props = defineProps<{
    name: string;
    label: string;
    type: string;
    error?: string;
    repeaterFields: {
        name: string;
        type: string;
        label: string;
    }[];
}>();

// On prend le name de chaque repeaterField, on créé un objet avec ces name comme clé et une valeur vide ou null ?
const keys = props.repeaterFields.map((field) => field.name) ?? [];

type Field = {
    [key in (typeof keys)[number]]: string | null;
};

const model = defineModel<Field[]>({ default: [] });

const draggedIndex = defineModel<number | null>('dragged', { default: null });

const addRow = () => {
    const newRow = Object.fromEntries(keys.map((key) => [key, null])) as Field;

    // TODO: Refactor ???
    model.value = [...model.value, newRow];
};

const removeField = (index: number) => {
    model.value.splice(index, 1);
};

const dragStart = (index: number, event: DragEvent) => {
    draggedIndex.value = index;

    const nodeElement = (event.currentTarget as HTMLElement).parentNode as HTMLElement;

    if (event.dataTransfer && nodeElement) {
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setDragImage(nodeElement, 20, 50);
    }
};

const drop = (index: number) => {
    if (draggedIndex.value !== null) {
        const temp = model.value[draggedIndex.value];

        model.value.splice(draggedIndex.value, 1);
        model.value.splice(index, 0, temp);

        draggedIndex.value = null;
    }
};
</script>

<template>
    <div class="flex flex-col gap-2">
        <span class="text-md pl-1.5 font-medium">{{ label }}</span>
        <div class="flex flex-col gap-2 rounded-md">
            <div
                @dragover.prevent
                @drop="drop(rowIndex)"
                v-for="(row, rowIndex) in model"
                :key="rowIndex"
                class="border-secondary-300 flex items-center gap-2 rounded-md border-1 px-2 py-3"
            >
                <div draggable="true" @dragstart="(e) => dragStart(rowIndex, e)" class="cursor-move">
                    <IconGrip />
                </div>
                <div class="flex w-full flex-col gap-2">
                    <template v-for="field in repeaterFields" :key="field.name">
                        <component
                            :is="getInputCpt(field.type)"
                            :type="field.type"
                            :name="field.name"
                            :label="field.label"
                            v-model="model[rowIndex][field.name]"
                            inline
                        />
                    </template>
                </div>

                <Action tag="button" variant="icon" @click.prevent="removeField(rowIndex)">
                    <IconClose />
                </Action>
            </div>

            <Action tag="button" variant="primary" @click.prevent="addRow">Add Field</Action>
        </div>
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped></style>
