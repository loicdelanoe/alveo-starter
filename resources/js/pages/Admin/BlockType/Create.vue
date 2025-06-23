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
import { Form, useFieldStore } from '@/stores/field';

defineProps<{
    collectionTypes: string[];
}>();

const form = useForm<Form>({
    name: '',
    type: '',
    component: '',
    fields: [],
});

const fieldStore = useFieldStore();

const removeField = (index: number) => {
    form.fields.splice(index, 1);
};

watch(
    () => form.name,
    (newTitle) => {
        form.type = slugify(newTitle);
    },
);

const isOpen = ref(false);
</script>

<template>
    <PanelLayout title="Create block type">
        <template #action>
            <Action tag="button" variant="primary" @click.prevent="form.post(route('admin.block-type.store'))">
                <IconPlus />
                Create Block Type
            </Action>
        </template>

        <form>
            <section class="mb-8">
                <h3 class="mb-2 text-xl font-medium">General</h3>
                <Container class="gap-2 md:flex-row md:gap-4 flex flex-col">
                    <InputLabel type="text" label="Name" name="name" :error="form.errors.name" v-model="form.name" />
                    <InputLabel
                        type="text"
                        label="Type"
                        name="type"
                        :error="form.errors.type"
                        v-model="form.type"
                        hint="A unique identifier for the block type"
                    />
                </Container>
                <!-- <InputLabel
              type="text"
              label="Component"
              name="component"
              :error="form.errors.component"
              v-model="form.component"
              hint="Name of the Vue component to be used for this block type"
            /> -->
            </section>

            <!-- Fields -->
            <section class="mb-8">
                <div class="flex items-center justify-between">
                    <h3 class="mb-2 text-xl font-medium">Fields</h3>
                    <Modal
                        variant="primary"
                        label="Add Field"
                        title="Add Field"
                        position="left"
                        size="xl"
                        v-model="isOpen"
                        icon="plus"
                        @close-modal="fieldStore.resetField"
                    >
                        <AsideField :form="form" @close-modal="isOpen = false" />
                    </Modal>
                </div>
                <span v-if="form.errors.fields" class="font-medium text-red-500">
                    {{ form.errors.fields }}
                </span>
                <ul class="gap-4 flex flex-col">
                    <li
                        v-for="(field, index) in form.fields"
                        :key="index"
                        class="border-secondary-200 gap-2 rounded-lg bg-white p-4 font-mono flex items-center border"
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
