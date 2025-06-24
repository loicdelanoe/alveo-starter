<script setup lang="ts">
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import Table from '@/components/admin/Ui/Table.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Form } from '@/types/models/form';
import { PaginateCollection } from '@/types/pagination';

defineProps<{
    forms: PaginateCollection<Form>;
}>();
</script>

<template>
    <PanelLayout title="Forms">
        <template #action>
            <Action tag="Link" variant="primary" size="normal" :href="route('admin.form.create')">
                <IconPlus />
                Create Form
            </Action>
        </template>

        <Table
            collection-name="form"
            data-name="forms"
            :collection="forms"
            :columns="['Name', 'Slug', 'Created at', 'Updated at']"
            column-link="slug"
            route-name="admin.form.show"
            hasSearch
        />
    </PanelLayout>
</template>

<style scoped></style>
