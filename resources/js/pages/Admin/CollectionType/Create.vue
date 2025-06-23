<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import AsideField from '@/components/admin/Form/AsideField.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Container from '@/components/admin/Ui/Container.vue';
import { slugify } from '@/components/admin/utils/text';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Form } from '@/stores/field';

const isOpen = ref(false);

const form = useForm<Form>({
    name: '',
    type: '',
    fields: [],
});

watch(
    () => form.name,
    (newTitle) => {
        form.type = slugify(newTitle);
    },
);

const removeField = (index: number) => {
    form.fields.splice(index, 1);
};
</script>

<template>
    <PanelLayout title="Create collection type">
        <template #action>
            <Action tag="button" variant="primary" class="w-full" @click.prevent="form.post(route('admin.collection-type.store'))">
                <IconPlus />
                Create Collection Type
            </Action>
        </template>
        <form class="flex flex-col gap-4">
            <section class="mb-8">
                <h2 class="mb-2 text-xl font-medium">General</h2>
                <Container class="flex flex-col gap-2 md:flex-row md:gap-4">
                    <InputLabel label="Name" name="name" type="text" v-model="form.name" :error="form.errors.name" />
                    <InputLabel
                        label="Type"
                        name="type"
                        type="text"
                        v-model="form.type"
                        :error="form.errors.type"
                        hint="A unique identifier for the collection type"
                    />
                </Container>
            </section>

            <!-- Fields -->
            <section class="mb-8">
                <div class="flex items-center justify-between">
                    <h2 class="mb-2 text-xl font-medium">Fields</h2>
                    <Modal variant="primary" label="Add Field" title="Add Field" size="xl" v-model="isOpen" position="left" icon="plus">
                        <AsideField :form="form" @close-modal="isOpen = false" />
                    </Modal>
                </div>
                <span v-if="form.errors.fields" class="font-medium text-red-500">
                    {{ form.errors.fields }}
                </span>
                <ul class="flex flex-col gap-4">
                    <li
                        v-for="(field, index) in form.fields"
                        :key="index"
                        class="border-secondary-200 flex items-center gap-2 rounded-lg border bg-white p-4 font-mono"
                    >
                        [{{ field.type }}] {{ field.label }} ({{ field.name }})
                        <Action class="ml-auto" tag="button" :variant="['delete', 'icon']" @click.prevent="removeField(index)">
                            <IconTrash />
                        </Action>
                    </li>
                </ul>
            </section>
        </form>
    </PanelLayout>
</template>

<style scoped></style>
