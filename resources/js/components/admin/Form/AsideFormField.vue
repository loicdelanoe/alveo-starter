<script setup lang="ts">
import CheckboxInput from '@/components/admin/Form/CheckboxInput.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import SelectInput from '@/components/admin/Form/SelectInput.vue';
import { FormField } from '@/types/models/form';
import { ref } from 'vue';

const emit = defineEmits<{
    (e: 'addField', value: FormField): void;
    (e: 'closeModal'): void;
}>();

const options = [
    { label: 'Text', value: 'text' },
    { label: 'Email', value: 'email' },
    { label: 'Number', value: 'number' },
    { label: 'Textarea', value: 'textarea' },
    { label: 'Select', value: 'select' },
    { label: 'Checkbox', value: 'checkbox' },
    { label: 'Radio', value: 'radio' },
];

const hasErrors = ref(false);
const errors = ref({
    type: '',
    name: '',
    label: '',
});
const field = ref<FormField>({
    type: '',
    name: '',
    label: '',
    validation: [],
});

const validate = () => {
    errors.value = {
        type: '',
        name: '',
        label: '',
    };

    let valid = true;

    if (!field.value.type) {
        errors.value.type = 'The type is required';
        valid = false;
    }

    if (!field.value.name) {
        errors.value.name = 'The name is required';
        valid = false;
    }

    if (!field.value.label) {
        errors.value.label = 'The label is required';
        valid = false;
    }

    hasErrors.value = !valid;

    return valid;
};

const addField = () => {
    const isValid = validate();

    if (!isValid) {
        return;
    }

    emit('addField', { ...field.value });

    field.value = {
        type: '',
        name: '',
        label: '',
        validation: [],
    };

    emit('closeModal');
};
</script>

<template>
    <div class="gap-2 flex flex-col">
        <SelectInput label="Type" name="type" :options="options" v-model="field.type" :error="errors.type" />
        <InputLabel
            label="Label"
            name="label"
            type="text"
            placeholder="Subject"
            v-model="field.label"
            :error="errors.label"
            hint="The name appearing on the admin panel"
        />
        <InputLabel
            label="Name"
            name="name"
            type="text"
            placeholder="subject"
            v-model="field.name"
            :error="errors.name"
            hint="A unique identifier for the field"
        />

        <div class="gap-2 mt-2 flex flex-col">
            <p class="text-xl font-medium">Validation</p>
            <CheckboxInput label="Required" name="required" v-model="field.validation" value="required" />
        </div>

        <Action tag="button" variant="primary" @click="addField">Add Field</Action>
    </div>
</template>

<style scoped></style>
