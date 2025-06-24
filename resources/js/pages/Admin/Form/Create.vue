<script setup lang="ts">
import PanelLayout from '@/Layouts/PanelLayout.vue';
import AsideFormField from '@/components/admin/Form/AsideFormField.vue';
import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import { FormField } from '@/types/models/form';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs = [
    {
        label: 'Forms',
        href: route('admin.form.index'),
    },
];

const fieldsModal = ref(false);

const form = useForm({
    name: '',
    slug: '',
    fields: [] as FormField[],
    success_message: '',
});

const onSubmit = () => {
    form.post(route('admin.form.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const handleAddField = (newField: FormField) => {
    form.fields.push(newField);
};

const removeField = (index: number) => {
    form.fields.splice(index, 1);
};
</script>

<template>
    <PanelLayout title="Forms">
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbs" />
        </template>

        <template #action>
            <Action tag="button" variant="primary" @click="onSubmit">
                <IconPlus />
                Create Form
            </Action>
        </template>

        <Container tag="section" class="gap-4 md:flex-row md:gap-6 mb-4 flex flex-col">
            <h3 class="sr-only">General</h3>
            <InputLabel label="Name" name="name" type="text" placeholder="Contact…" v-model="form.name" :error="form.errors.name" />
            <InputLabel
                label="Slug"
                name="slug"
                type="text"
                placeholder="contact-form…"
                v-model="form.slug"
                :error="form.errors.slug"
                hint="A unique identifier"
            />
        </Container>

        <Container class="mb-4">
            <InputLabel
                label="Success Message"
                name="success_message"
                type="text"
                placeholder="Thank you for your submission!"
                v-model="form.success_message"
                :error="form.errors.success_message"
                class="w-full"
            />
        </Container>

        <section>
            <div class="md:flex-row md:justify-between mb-4 flex flex-col items-center">
                <h3 class="text-xl font-medium">Fields</h3>
                <Modal variant="outline" size="lg" label="Add Field" title="Add Field" position="left" icon="plus" v-model="fieldsModal">
                    <AsideFormField @addField="handleAddField" @close-modal="fieldsModal = false" />
                </Modal>
            </div>

            <ErrorMessage :error="form.errors.fields" />

            <ul>
                <Container tag="li" v-for="(field, index) in form.fields" :key="field.name" class="flex items-center justify-between">
                    {{ field.label }}

                    <Action class="ml-auto" tag="button" :variant="['delete', 'icon']" @click.prevent="removeField(index)">
                        <IconTrash />
                    </Action>
                </Container>
            </ul>
        </section>
    </PanelLayout>
</template>

<style scoped></style>
