import { LaravelCollection } from './collection';

export type FormField = {
    type: string;
    name: string;
    label: string;
    validation: string[];
    options?: {
        label: string;
        value: string;
    }[];
    [key: string]: any; // Allow additional properties
};

export interface Form extends LaravelCollection {
    name: string;
    slug: string;
    fields: FormField[];
    success_message?: string;
    [key: string]: any; // Allow additional properties
}

export interface FormFormData {
    name: string;
    slug: string;
    fields: FormField[];
    success_message?: string;
    [key: string]: string | FormField[] | undefined; // Allow additional properties
}
