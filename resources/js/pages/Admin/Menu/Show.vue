<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import AsidePage from '@/components/admin/Form/AsidePage.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { deleteItem } from '@/utils/utils';

import AsideLinks from '@/components/admin/Form/AsideLinks.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconGrip from '@/components/admin/Icon/IconGrip.vue';
import SlugCell from '@/components/admin/Ui/Table/SlugCell.vue';
import type { Menu } from '@/types/models/menu';
import type { Page } from '@/types/models/page';
import { ref } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';

const props = defineProps<{
    menu: Menu;
    pages: Page[];
}>();

const linksModal = ref(false);
const pagesModal = ref(false);

const form = useForm({
    name: props.menu.name,
    slug: props.menu.slug,
    active: props.menu.active,
    navigations: props.menu.navigations || [],
});

const onSubmit = () => {
    form.patch(route('admin.menu.update', props.menu.slug), {
        preserveScroll: true,
    });
};

const handleAddLink = (link: any) => {
    console.log('Adding link:', link);
    form.navigations.push({
        menu_id: props.menu.id,
        parent_id: null,
        navigable_id: link.id,
        navigable_type: 'link',
        order: form.navigations.length,
        navigable: {
            id: link.id,
            title: link.title,
            url: link.url,
            blank: link.blank,
        },
    });

    linksModal.value = false;
};

const handleAddPages = (pages: Page[]) => {
    pages.forEach((page) => {
        form.navigations.push({
            menu_id: props.menu.id,
            parent_id: null,
            navigable_id: page.id,
            navigable_type: 'page',
            order: form.navigations.length,
            navigable: {
                id: page.id,
                title: page.title,
                slug: page.slug,
            },
        });
    });

    pagesModal.value = false;
};

const removeNavigation = (index: number) => {
    if (form.navigations[index].navigable_type === 'link') {
        // delete in db the link
        deleteItem(route('admin.link.destroy', form.navigations[index].navigable.id), 'Are you sure you want to delete this link?');
    }
    form.navigations.splice(index, 1);
};

const pagesNotInMenu = ref(
    props.pages.filter((page) => !form.navigations.some((nav) => nav.navigable_id === page.id && nav.navigable_type === 'page')),
);
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

            <!-- Entries -->
            <section>
                <div class="mb-4 flex items-center">
                    <h3 class="text-2xl font-medium">Entries</h3>

                    <div class="gap-2 ml-auto flex items-center">
                        <Modal variant="primary" size="2xl" label="Add Pages" title="Add Pages" position="left" v-model="pagesModal">
                            <AsidePage @add-pages="handleAddPages" :pages="pagesNotInMenu" />
                        </Modal>
                        <Modal variant="outline" size="2xl" label="Add Custom Link" title="Add Custom Link" position="left" v-model="linksModal">
                            <AsideLinks @add-link="handleAddLink" />
                        </Modal>
                    </div>
                </div>

                <Container>
                    <VueDraggable tag="ul" class="gap-2 flex flex-col" ref="el" v-model="form.navigations">
                        <Container
                            tag="li"
                            class="py-1 flex items-center justify-between hover:cursor-move"
                            v-for="(navigation, index) in form.navigations"
                            :key="JSON.stringify(navigation)"
                        >
                            <div class="gap-2 flex items-center">
                                <IconGrip />
                                <span class="font-medium">{{ navigation.navigable.title }}</span>
                                <SlugCell :content="navigation.navigable.slug ?? navigation.navigable.url" />
                            </div>

                            <Action tag="button" variant="icon" @click="removeNavigation(index)">
                                <IconClose />
                            </Action>
                        </Container>
                    </VueDraggable>
                </Container>
            </section>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
