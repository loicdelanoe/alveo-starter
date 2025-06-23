<script setup lang="ts">
import { InertiaForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { slugify } from '../utils/text';

import Action from '@/components/admin/Action.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import SelectInput from '@/components/admin/Form/SelectInput.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconChevronDown from '@/components/admin/Icon/IconChevronDown.vue';

import { Form, useFieldStore } from '@/stores/field';

const props = defineProps<{
    form: InertiaForm<Form>;
}>();

const emit = defineEmits(['closeModal']);
const page = usePage();
const fieldStore = useFieldStore();
const fieldTypes: string[] = page.props.fields;

const submitField = () => {
    const success = fieldStore.pushField(props.form);

    if (success) {
        emit('closeModal');
    }
};

watch(
    () => fieldStore.field.label,
    (newTitle) => {
        fieldStore.field.name = slugify(newTitle);
    },
);
</script>

<template>
    <aside class="gap-6 flex flex-col">
        <!-- Basic Fields -->
        <div class="gap-2 flex flex-col">
            <SelectInput name="type" label="Type" :options="fieldTypes" v-model="fieldStore.field.type" :error="fieldStore.errors.type" />
            <InputLabel
                type="text"
                label="Label"
                name="label"
                v-model="fieldStore.field.label"
                :error="fieldStore.errors.label"
                hint="The name appearing on the admin panel"
            />
            <InputLabel
                type="text"
                label="Name"
                name="name"
                v-model="fieldStore.field.name"
                :error="fieldStore.errors.name"
                hint="A unique identifier for the field"
            />
        </div>

        <!-- Repeater Fields -->
        <div v-if="fieldStore.field.type === 'repeater'" class="gap-3 p-4 rounded-md bg-secondary-50 flex flex-col">
            <div class="flex items-center justify-between">
                <span class="text-xl font-medium">Repeater fields</span>
                <IconChevronDown />
            </div>

            <div v-for="(field, index) in fieldStore.field.repeaterFields" :key="index" class="gap-2 flex">
                <SelectInput name="type" label="Type" :options="fieldTypes" v-model="field.type" :error="fieldStore.errors.type" class="min-w-1/4" />
                <InputLabel type="text" label="Label" name="label" v-model="field.label" :error="fieldStore.errors.label" />
                <InputLabel type="text" label="Name" name="name" v-model="field.name" :error="fieldStore.errors.name" />
            </div>

            <Action
                tag="button"
                variant="primary"
                @click="
                    fieldStore.field.repeaterFields?.push({
                        type: 'text',
                        label: '',
                        name: '',
                        validation: [],
                    })
                "
            >
                Add Repeater Field
            </Action>
        </div>

        <!-- Validation Toggles -->
        <div class="gap-2 flex flex-col">
            <p class="text-xl font-medium">Validation</p>
            <ToggleInput label="Required" name="required" v-model="fieldStore.field.validation" value="required" />
        </div>

        <!-- Submit Button -->
        <Action tag="button" variant="primary" @click.prevent="submitField"> Add Field </Action>
    </aside>
</template>
