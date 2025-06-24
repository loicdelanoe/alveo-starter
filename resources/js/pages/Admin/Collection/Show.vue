<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import FieldBuilder from '@/components/admin/Form/FieldBuilder.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import SelectInput from '@/components/admin/Form/SelectInput.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import TabsHead from '@/components/admin/Ui/TabsHead.vue';
import VisitLink from '@/components/admin/Ui/VisitLink.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { deleteItem } from '@/utils/utils';

import { Collection } from './Index.vue';

const { collection, collectionType } = defineProps<{
    collection: Collection;
    collectionType: any;
}>();

const tab = ref(0);
const tabs = [
    { name: 'General', id: 0 },
    { name: 'SEO', id: 1 },
];
const status = ['draft', 'published'];

const form = useForm({
    status: collection.status,
    title: collection.title,
    slug: collection.slug,
    meta_title: collection.meta_title,
    meta_description: collection.meta_description,
    og_type: collection.og_type,
    content: collection.content,
});

const onSubmit = () => {
    form.patch(
        route('admin.collection.update', {
            collectionType: collectionType.type,
            collection: collection.slug,
        }),
        {
            preserveScroll: true,
        },
    );
};

const links = [
    {
        label: collectionType.name,
        href: route('admin.collection-type.show', collectionType.type),
    },
    {
        label: collection.title,
        href: route('admin.collection.show', {
            collectionType: collectionType.type,
            collection: collection.slug,
        }),
    },
];
</script>

<template>
    <PanelLayout :title="collection.title">
        <template #breadcrumbs>
            <Breadcrumbs :links="links" />
        </template>

        <template #action>
            <Action
                tag="button"
                :variant="['delete', 'icon']"
                @click.prevent="deleteItem(route('admin.collection.destroy', collection.slug), 'Do you really want to delete this collection?', form)"
            >
                <IconTrash />
            </Action>
        </template>

        <div class="gap-5 lg:grid-cols-3 relative grid grid-cols-1">
            <div class="lg:col-span-2 flex flex-col">
                <TabsHead :tabs="tabs" v-model="tab" />
                <!-- General -->
                <!-- Title and Slug -->
                <div v-show="tab === 0" class="gap-6 flex w-full flex-col">
                    <Container tag="section" class="gap-4 md:flex-row flex flex-col">
                        <h3 class="sr-only">General</h3>
                        <InputLabel label="Title" name="title" type="text" v-model="form.title" :error="form.errors.title" />
                        <InputLabel label="Slug" name="slug" type="text" v-model="form.slug" :error="form.errors.slug" />
                    </Container>

                    <!-- Fields -->
                    <Container tag="section" class="gap-4 flex flex-col">
                        <h3 class="mb-2 text-2xl font-medium">Fields</h3>
                        <div v-for="field in collectionType.fields" class="gap-4 flex flex-col" :key="JSON.stringify(field)">
                            <FieldBuilder :field="field" :form="form" />
                        </div>
                    </Container>
                </div>

                <!-- SEO -->
                <Container v-show="tab === 1" tag="section" class="gap-4 flex flex-col">
                    <h3 class="text-xl font-medium sr-only">SEO</h3>
                    <InputLabel label="Meta title" name="meta_title" type="text" v-model="form.meta_title" :error="form.errors.meta_title" />
                    <TextAreaInput
                        label="Meta description"
                        name="meta_description"
                        type="text"
                        v-model="form.meta_description"
                        :error="form.errors.meta_description"
                    />
                    <InputLabel label="Open Graph type" name="og_type" type="text" v-model="form.og_type" :error="form.errors.og_type" />
                </Container>
            </div>

            <!-- Status -->
            <Container tag="aside" class="gap-2 lg:sticky lg:top-7 flex w-full flex-col self-start">
                <h3 class="sr-only">Aside</h3>
                <SelectInput name="status" label="Status" :options="status" v-model="form.status" :error="form.errors.status" />
                <p v-if="form.isDirty" class="font-medium text-orange-600 italic">Form has unsaved changes</p>
                <Action tag="button" @click="onSubmit" variant="primary"> Save </Action>
                <VisitLink
                    class="mx-auto"
                    :href="
                        route('page.collection', {
                            collection: collectionType.type,
                            slug: collection.slug,
                        })
                    "
                />
            </Container>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
