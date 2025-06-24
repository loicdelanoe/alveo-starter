<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from '@vue/reactivity';
import { ref } from 'vue';

import CheckboxInput from '@/components/admin/Form/CheckboxInput.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import MenuAction from '@/components/admin/Form/MenuAction.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconPlus from '@/components/admin/Icon/IconPlus.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import Draggable from '@/components/admin/Ui/Draggable.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import ActionCreate from '@/pages/Admin/Action/Create.vue';
import { deleteItem } from '@/utils/utils';

import type { Menu } from '@/types/models/menu';
import type { Page } from '@/types/models/page';

const props = defineProps<{
    menu: Menu;
    pages: Page[];
}>();

const selectedPages = ref<number[]>([]);
const isOpen = ref(false);
const actionModal = ref(false);
const draggedIndex = ref<number | null>(null);

const filteredPages = computed(() => {
    return props.pages.filter((page) => {
        return !props.menu.pages.some((menuPage) => page.id === menuPage.id);
    });
});

const formattedFormPages = computed(() => {
    return form.pages.map((id) => props.pages.find((p) => p.id === id)).filter((p): p is Page => !!p); // Remove undefined (in case of mismatch)
});

const form = useForm({
    name: props.menu.name,
    slug: props.menu.slug,
    active: props.menu.active,
    pages: props.menu.pages.map((page) => page.id),
});

const removeFromMenu = (index: number) => {
    form.pages.splice(index, 1);
};

const onSubmit = () => {
    form.patch(route('admin.menu.update', props.menu.slug), {
        preserveScroll: true,
        // preserveState: false,
    });
};

const addPages = () => {
    form.pages.push(...selectedPages.value);

    selectedPages.value = [];
    isOpen.value = false;
};
</script>

<template>
    <PanelLayout :title="menu.name">
        <template #breadcrumbs>
            <Breadcrumbs
                :links="[
                    { label: 'Menus', href: route('admin.menu.index') },
                    { label: props.menu.name, href: route('admin.menu.show', props.menu.id) },
                ]"
            />
        </template>

        <template #action>
            <Can permission="edit menus">
                <span v-if="form.isDirty" class="font-medium text-orange-600 italic"> Form has unsaved changes </span>
                <ToggleInput label="Active" name="active" type="checkbox" v-model="form.active" :error="form.errors.active" reverse />
                <Action tag="button" variant="primary" @click="onSubmit">Save</Action>
            </Can>
            <Can permission="delete menus">
                <Action
                    tag="button"
                    :variant="['delete', 'icon']"
                    @click="deleteItem(route('admin.menu.destroy', props.menu.slug), 'Are you sure you want to delete this menu?', form)"
                >
                    <IconTrash />
                </Action>
            </Can>
        </template>

        <div class="gap-6 flex flex-col">
            <Container class="gap-4 md:flex-row md:gap-6 flex flex-col">
                <InputLabel label="Name" name="name" type="text" v-model="form.name" :error="form.errors.name" />
                <InputLabel label="Slug" name="slug" type="text" v-model="form.slug" :error="form.errors.slug" hint="A unique identifier" />
            </Container>

            <!-- Pages -->
            <div class="gap-4 md:grid-cols-2 grid grid-cols-1">
                <section class="gap-4 flex flex-col">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-medium">Pages</h2>
                        <Can permission="edit menus">
                            <Modal
                                label="Add Pages"
                                title="Choose pages"
                                variant="outline"
                                size="lg"
                                position="left"
                                v-model="isOpen"
                                icon="plus"
                                button-size="small"
                            >
                                <ul v-if="pages">
                                    <li v-for="page in filteredPages" :key="`page-${page.id}`">
                                        <CheckboxInput
                                            :label="page.title"
                                            :name="`page-${page.id}`"
                                            :value="page.id"
                                            v-model="selectedPages"
                                            class="page-checkbox pl-3 w-full cursor-pointer"
                                        />
                                    </li>
                                </ul>

                                <template #footer>
                                    <Action tag="button" variant="primary" @click="addPages">
                                        <IconPlus />
                                        Add {{ selectedPages.length }} page(s)
                                    </Action>
                                </template>
                            </Modal>
                        </Can>
                    </div>

                    <!-- Drag n Drop -->
                    <ul class="gap-2 flex flex-col">
                        <li v-for="(page, index) in formattedFormPages" :key="page.id">
                            <Draggable
                                :class="{ 'opacity-0': draggedIndex === index }"
                                :index="index"
                                :pos-x="20"
                                :pos-y="25"
                                v-model="form.pages"
                                v-model:dragged="draggedIndex"
                            >
                                {{ page.title }}
                                <Action tag="button" variant="icon" class="ml-auto" @click="removeFromMenu(index)">
                                    <IconClose />
                                </Action>
                            </Draggable>
                        </li>
                    </ul>
                </section>
                <section class="gap-4 flex flex-col">
                    <div class="gap-2 flex items-center justify-between">
                        <h3 class="text-xl font-medium">Actions</h3>
                        <Modal label="Add Action" title="Add Action" variant="primary" size="xl" position="left" icon="plus" v-model="actionModal">
                            <ActionCreate :menu-form="form" :menu-id="menu.id" @close-modal="actionModal = false" />
                        </Modal>
                    </div>
                    <ul class="gap-2 flex flex-col">
                        <li v-for="(action, index) in menu.actions" :key="action.id">
                            <MenuAction :action="action" :index="index" />
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
