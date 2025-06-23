<script setup lang="ts" generic="T extends LaravelCollection">
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import CheckboxInput from '@/components/admin/Form/CheckboxInput.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Pagination from '@/components/admin/Navigation/Pagination.vue';
import Dropdown from '@/components/admin/Ui/Dropdown.vue';
import SlugCell from '@/components/admin/Ui/Table/SlugCell.vue';
import StatusCell from '@/components/admin/Ui/Table/StatusCell.vue';
import TitleCell from '@/components/admin/Ui/Table/TitleCell.vue';
import { LaravelCollection } from '@/types/models/collection';
import { PaginateCollection } from '@/types/pagination';

import type { Component } from 'vue';

const props = defineProps<{
    columns: string[];
    collection: PaginateCollection<T>;
    columnLink: string | string[];
    routeName: string;
    collectionName?: string;
    dataName: string;
    hasSearch?: boolean;
}>();

const columnCpt = {
    status: StatusCell,
    slug: SlugCell,
    title: TitleCell,
    name: TitleCell,
} as {
    [key: string]: Component;
};

const search = ref('');
const isAllSelected = ref(false);
const bulkActionsForm = useForm({
    selected: [] as number[],
});

const onSearch = () => {
    router.reload({
        data: {
            search: search.value,
        },
        only: [props.dataName],
    });
};

const formatedColumns = props.columns.map((column) => {
    return column.replace('content.', '');
});

const columnIdentifier = props.columns.map((column) => {
    return column.replace(' ', '_').toLowerCase();
});

const getRoute = (item: any, columnLink: string | string[]) => {
    if (Array.isArray(columnLink)) {
        return route(props.routeName, [columnLink[0], item[columnLink[1]]]);
    }

    return route(props.routeName, item[columnLink]);
};

const getColumn = (item: any, column: string) => {
    if (column.includes('content.')) {
        const keys = column.split('.');

        return item[keys[0]][keys[1]];
    }

    return item[column];
};

const bulkDelete = () => {
    if (bulkActionsForm.selected.length === 0) {
        return;
    }

    bulkActionsForm.selected.forEach((item) => {
        bulkActionsForm.delete(route(`admin.${props.collectionName}.destroy`, item));
    });

    bulkActionsForm.selected = [];
    isAllSelected.value = false;
};

watch(isAllSelected, () => {
    if (isAllSelected.value) {
        bulkActionsForm.selected = props.collection.data.map((item) => item[props.columnLink]);
    } else {
        bulkActionsForm.selected = [];
    }
});
</script>

<template>
    <div class="flex flex-col gap-3">
        <!-- Search bar -->
        <div class="flex flex-col items-start gap-4 md:flex-row md:items-end">
            <div v-if="hasSearch" class="flex w-full md:w-80">
                <InputLabel label="Search" name="search" type="search" placeholder="Home…" v-model="search" @keyup="onSearch" />
            </div>
            <Dropdown class="md:ml-auto">
                <button
                    class="hover:bg-secondary-100 flex cursor-pointer items-center gap-2 rounded-md px-3.5 py-2 text-red-700 transition"
                    @click="bulkDelete"
                >
                    <IconTrash />
                    Delete item(s)
                </button>
            </Dropdown>
        </div>

        <!-- Table -->
        <div class="border-secondary-200 relative overflow-auto rounded-xl border bg-white p-1.5 py-2">
            <table class="bg-secondary-950 w-full rounded-lg text-left">
                <!-- Thead -->
                <thead class="text-white">
                    <tr>
                        <th class="relative z-1 p-3">
                            <CheckboxInput label="all" name="all" id="all" hidden-label v-model="isAllSelected" />
                        </th>
                        <th v-for="column in formatedColumns" :key="column" scope="col" class="p-3">
                            <span class="flex gap-2 font-medium">
                                {{ column }}
                            </span>
                        </th>
                    </tr>
                </thead>

                <!-- TBody -->
                <tbody>
                    <tr
                        v-for="(item, index) in collection.data"
                        :key="item.id"
                        class="hover:bg-secondary-50 not-last:border-secondary-100 relative cursor-pointer bg-white transition not-last:border-b"
                    >
                        <td class="p-3">
                            <CheckboxInput
                                :label="`item-${index}`"
                                :name="`item-${index}`"
                                :id="`item-${index}`"
                                :value="item[columnLink]"
                                hidden-label
                                v-model="bulkActionsForm.selected"
                            />
                            <Link class="absolute inset-0" :href="getRoute(item, columnLink)">
                                <span class="sr-only">link</span>
                            </Link>
                        </td>
                        <td v-for="column in columnIdentifier" :key="column" class="max-w-52 truncate p-3 text-ellipsis">
                            <template v-if="columnCpt[column]">
                                <component v-if="columnCpt[column]" :is="columnCpt[column]" :content="getColumn(item, column)" />
                            </template>

                            <template v-else>
                                <span v-if="Array.isArray(getColumn(item, column))"> {{ getColumn(item, column).length }} item(s) </span>
                                <span v-else>{{ getColumn(item, column) }}</span>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination v-if="collection.links.length >= 4" :links="collection.links" />
    </div>
</template>

<style scoped>
/*
|------------------------------------------------------
| Workaround to make table row clickable in Safari
|------------------------------------------------------
|
| source: https://mtsknn.fi/blog/relative-tr-in-safari/
*/
tbody tr {
    transform: translate(0);
    clip-path: inset(0);
}

thead th:last-child {
    border-top-right-radius: var(--radius-lg);
}

thead th:first-child {
    border-top-left-radius: var(--radius-lg);
}

tbody tr:last-child td:first-child {
    border-bottom-left-radius: var(--radius-lg);
}

tbody tr:last-child td:last-child {
    border-bottom-right-radius: var(--radius-lg);
}

tbody tr:first-child td:first-child {
    border-top-left-radius: var(--radius-lg);
}

tbody tr:first-child td:last-child {
    border-top-right-radius: var(--radius-lg);
}
</style>
