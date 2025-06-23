import { fields } from '@/config/fields';
import type { InertiaForm } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export interface Field {
    name: string;
    type: string;
    label: string;
    validation: string[];
    repeaterFields?: Field[];
}

export interface Form {
    name: string;
    type: string;
    fields: Field[];
    [key: string]: any; // Allow additional properties
}

interface Errors {
    name?: string;
    type?: string;
    label?: string;
}

const defaultField: Field = {
    name: '',
    type: '',
    label: '',
    validation: [],
};

export const useFieldStore = defineStore('field', () => {
    const field = ref<Field>({ ...defaultField });
    const errors = ref<Errors>({});

    const resetField = () => {
        field.value = { ...defaultField };
        errors.value = {};
    };

    const validate = (): boolean => {
        let valid = true;
        errors.value = {};

        if (!field.value.name.trim()) {
            errors.value.name = 'Name is required';
            valid = false;
        }

        if (!field.value.type.trim()) {
            errors.value.type = 'Type is required';
            valid = false;
        }

        if (!field.value.label.trim()) {
            errors.value.label = 'Label is required';
            valid = false;
        }

        return valid;
    };

    const pushField = (form: InertiaForm<Form>): boolean => {
        if (!validate()) return false;

        form.fields.push(JSON.parse(JSON.stringify(field.value))); // deep copy
        resetField();
        return true;
    };

    const addRepeaterField = (targetField: Field) => {
        if (!targetField.repeaterFields) {
            targetField.repeaterFields = [];
        }

        targetField.repeaterFields.push({
            name: '',
            type: '',
            label: '',
            validation: [],
        });
    };

    watch(
        () => field.value.type,
        (newType) => {
            if (!newType) return;

            // Reset repeaterFields if not a repeater
            if (newType !== 'repeater') {
                delete field.value.repeaterFields;
            } else {
                addRepeaterField(field.value);
            }

            field.value.validation = fields.validation[newType] || [];
        },
    );

    return {
        field,
        errors,
        pushField,
        resetField,
        addRepeaterField,
        validate,
    };
});
