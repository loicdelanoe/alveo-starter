// composables/useDynamicForm.ts
import type { Form as FormType } from '@/types/models/form';
import { router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

/**
 * useDynamicForm is a composable function that manages a dynamic form based on a given form definition.
 * It initializes the form state, handles form submission, and manages validation errors.
 *
 * @param {FormType} formDefinition - The definition of the form, including fields and their properties.
 * @param {string} [errorBag='clientForm'] - The error bag name used for validation errors.
 * @returns {Object} An object containing the reactive form state, submit function, and validation errors.
 */
export function useDynamicForm(
    formDefinition: FormType,
    errorBag: string = 'clientForm',
): {
    form: {
        [k: string]: string;
    };
    onSubmit: () => void;
    errors: Record<string, any>;
} {
    // Initialize reactive form state with empty strings for all fields
    const form = reactive(Object.fromEntries(formDefinition.fields.map((field) => [field.name, ''])));

    const errors: Record<string, any> = usePage().props.errors[errorBag] || {};

    // Submit function: sends form data to the Laravel route named 'form.submit'
    const onSubmit = () => {
        return router.post(route('form.submit', formDefinition.slug), form, {
            errorBag,
            onSuccess: () => {
                // Reset form fields on success
                Object.keys(form).forEach((key) => (form[key] = ''));
            },
        });
    };

    return { form, onSubmit, errors };
}
