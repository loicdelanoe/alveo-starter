<script setup lang="ts">
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import Can from '@/components/admin/Permission/Can.vue';
import Table from '@/components/admin/Ui/Table.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Menu } from '@/types/models/menu';
import { PaginateCollection } from '@/types/pagination';

defineProps<{
    menus: PaginateCollection<Menu>;
}>();
</script>

<template>
    <PanelLayout title="Menus">
        <template #action>
            <Can permission="create menus">
                <Action tag="Link" variant="primary" :href="route('admin.menu.create')">
                    <IconPlus />
                    Create Menu
                </Action>
            </Can>
        </template>

        <Table
            :columns="['Name', 'Slug', 'Created At', 'Updated At']"
            collection-name="menu"
            data-name="menus"
            column-link="slug"
            :collection="menus"
            route-name="admin.menu.show"
            hasSearch
        />
    </PanelLayout>
</template>

<style scoped></style>
