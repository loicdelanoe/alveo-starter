<script setup lang="ts">
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Form } from '@/types/models/form';

import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Table from '@/components/admin/Ui/Table.vue';
import { PaginateCollection } from '@/types/pagination';

const props = defineProps<{
    form: Form;
    submissions: PaginateCollection<any>;
}>();

const links = [
    {
        label: 'Forms',
        href: route('admin.form.index'),
    },
    {
        label: props.form.name,
        href: route('admin.form.submissions.index', props.form.slug),
    },
];
</script>

<template>
    <PanelLayout :title="form.name">
        <template #breadcrumbs>
            <Breadcrumbs :links="links" />
        </template>
        <Table
            collection-name="form.submissions"
            data-name="formSubmissions"
            :collection="submissions"
            :columns="['Submitted At', 'Data', 'Created at', 'Updated at']"
            :column-link="[props.form.slug, 'id']"
            route-name="admin.form.submissions.show"
            hasSearch
        />
    </PanelLayout>
</template>

<style scoped></style>
