<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

import InputLabel from '@/components/admin/Form/InputLabel.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import { slugify } from '@/components/admin/utils/text';
import PanelLayout from '@/Layouts/PanelLayout.vue';

import Action from '@/components/admin/Action.vue';
import type { Page } from '@/types/models/page';

defineProps<{
    pages: Page[];
    navigableTypes: Record<string, string>;
}>();

const breadcrumbs = [
    {
        label: 'Menus',
        href: route('admin.menu.index'),
    },
];

const form = useForm({
    name: '',
    slug: '',
    active: false,
});

const onSubmit = () => {
    form.post(route('admin.menu.store'));
};

watch(
    () => form.name,
    (newTitle) => {
        form.slug = slugify(newTitle);
    },
);
</script>

<template>
    <PanelLayout title="Create">
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbs" />
        </template>

        <template #action>
            <ToggleInput label="Active" name="active" type="checkbox" v-model="form.active" :error="form.errors.active" reverse />
            <Action tag="button" variant="primary" @click="onSubmit">
                <IconPlus />
                Create Menu
            </Action>
        </template>

        <Container tag="section" class="gap-4 md:flex-row md:gap-6 flex flex-col">
            <h3 class="sr-only">General</h3>
            <InputLabel label="Name" name="name" type="text" placeholder="Main…" v-model="form.name" :error="form.errors.name" />
            <InputLabel
                label="Slug"
                name="slug"
                type="text"
                placeholder="main-menu…"
                v-model="form.slug"
                :error="form.errors.slug"
                hint="A unique identifier"
            />
        </Container>
    </PanelLayout>
</template>

<style scoped>
.page-checkbox :deep(label) {
    padding: 1.125rem 0.75rem;
}
</style>
