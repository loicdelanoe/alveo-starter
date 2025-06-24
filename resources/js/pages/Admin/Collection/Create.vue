<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import FieldBuilder from '@/components/admin/Form/FieldBuilder.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import SelectInput from '@/components/admin/Form/SelectInput.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import TabsHead from '@/components/admin/Ui/TabsHead.vue';
import { slugify } from '@/components/admin/utils/text';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { CollectionType } from '@/Pages/Admin/CollectionType/Index.vue';

const status = ['draft', 'published'];

const props = defineProps<{
    collectionType: CollectionType;
}>();

const tabs = [
    { name: 'General', id: 0 },
    { name: 'SEO', id: 1 },
];

const tab = ref(0);

const form = useForm({
    status: 'draft',
    title: '',
    slug: '',
    meta_title: '',
    meta_description: '',
    og_type: '',
    content: {},
});

watch(
    () => form.title,
    (newTitle) => {
        form.slug = slugify(newTitle);
    },
);

const onSubmit = () => {
    form.post(route('admin.collection.store', props.collectionType.type));
};

const links = [
    {
        label: props.collectionType.name,
        href: route('admin.collection-type.show', props.collectionType.type),
    },
];
</script>

<template>
    <PanelLayout title="Create">
        <template #breadcrumbs>
            <Breadcrumbs :links="links" />
        </template>

        <pre>{{ form }}</pre>

        <div class="gap-5 lg:grid-cols-3 relative grid grid-cols-1">
            <div class="lg:col-span-2 flex flex-col">
                <TabsHead :tabs="tabs" v-model="tab" />

                <!-- General -->
                <!-- Title and Slug -->
                <div v-show="tab === 0" class="gap-6 flex w-full flex-col">
                    <Container tag="section" class="gap-4 md:flex-row flex flex-col">
                        <h3 class="sr-only">General</h3>
                        <InputLabel label="Title" name="title" type="text" v-model="form.title" :error="form.errors.title" />
                        <InputLabel label="Slug" name="slug" type="text" v-model="form.slug" :error="form.errors.slug" hint="A unique identifier" />
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
                <Action tag="button" @click="onSubmit" variant="primary"> Save </Action>
            </Container>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
