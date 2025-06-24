<script setup lang="ts">
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Form } from '@/types/models/form';

import Table from '@/components/admin/Ui/Table.vue';
import { PaginateCollection } from '@/types/pagination';

const props = defineProps<{
    form: Form;
    submissions: PaginateCollection<any>;
}>();
</script>

<template>
    <PanelLayout :title="form.name">
        <Table
            collection-name="formSubmissions"
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
