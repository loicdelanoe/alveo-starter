<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';

import InputLabel from '@/components/admin/Form/InputLabel.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { deleteItem } from '@/utils/utils';

import AsideChildren from '@/components/admin/Form/Aside/AsideChildren.vue';
import AsideEditGroup from '@/components/admin/Form/Aside/AsideEditGroup.vue';
import AsideEditLink from '@/components/admin/Form/Aside/AsideEditLink.vue';
import AsideGroup from '@/components/admin/Form/Aside/AsideGroup.vue';
import AsideLink from '@/components/admin/Form/Aside/AsideLink.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconEdit from '@/components/admin/Icon/IconEdit.vue';
import IconGrip from '@/components/admin/Icon/IconGrip.vue';
import GlobalModal from '@/components/admin/Modal/GlobalModal.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import SlugCell from '@/components/admin/Ui/Table/SlugCell.vue';
import type { Menu } from '@/types/models/menu';
import type { Page } from '@/types/models/page';

const props = defineProps<{
    menu: Menu;
    menuEntries: any[];
    pages: Page[];
}>();

const linkModal = ref(false);
const groupModal = ref(false);
const childrenModal = ref(false);
const editGroupModal = ref(false);
const editChildModal = ref(false);

const isOpen = ref(false);
const linkIndex = ref(0);

const childIndex = ref(0);

const form = useForm({
    name: props.menu.name,
    slug: props.menu.slug,
    active: props.menu.active,
    entries: props.menuEntries ?? [],
    deletedLinks: [] as number[],
    deletedGroups: [] as number[],
});

const onSubmit = () => {
    form.patch(route('admin.menu.update', props.menu.slug), {
        preserveScroll: true,
    });
};

const handleAddLink = (link: { title: string; url: string; blank: boolean }) => {
    form.entries.push({ ...link });

    linkModal.value = false;
};

const handleAddGroup = (group: { name: string; slug: string }) => {
    form.entries.push({ ...group, links: [] });

    groupModal.value = false;
};

const handleAddChildren = (link: { title: string; url: string; blank: boolean; group_id: number }, groupId: number) => {
    console.log('Adding children link:', link, 'to group ID:', groupId);

    form.entries[form.entries.findIndex((entry) => entry.id === groupId)].links.push({
        ...link,
    });

    childrenModal.value = false;
};

const removeLink = (index: number) => {
    form.deletedLinks.push(form.entries[index].id);

    form.entries.splice(index, 1);
};

const removeGroup = (index: number) => {
    form.deletedGroups.push(form.entries[index].id);

    form.entries.splice(index, 1);
};

const openModal = (index: number) => {
    isOpen.value = true;

    linkIndex.value = index;
};

const openEditGroup = (index: number) => {
    editGroupModal.value = true;

    linkIndex.value = index;
};

const openChildGroup = (parentIndex: number, index: number) => {
    editChildModal.value = true;

    linkIndex.value = parentIndex;
    childIndex.value = index;
};

const updateData = (data: any) => {
    form.entries[linkIndex.value] = { ...form.entries[linkIndex.value], ...data };
};

const updateChild = (data: any) => {
    form.entries[linkIndex.value].links[childIndex.value] = { ...form.entries[linkIndex.value].links[childIndex.value], ...data };
};

const removeChild = (parentId: number, index: number) => {
    form.deletedLinks.push(form.entries[parentId].links[index].id);

    form.entries[parentId].links.splice(index, 1);
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

            <!-- Entries -->
            <section class="gap-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-medium">Links</h3>

                    <div class="gap-2 flex items-center">
                        <Modal label="Add Link" title="Add Link" variant="primary" size="2xl" position="left" v-model="linkModal">
                            <AsideLink :menu="menu" :menu-entries="menuEntries" @add-link="handleAddLink" />
                        </Modal>
                        <Modal label="Add Group" title="Add Group" variant="outline" size="2xl" position="left" v-model="groupModal">
                            <AsideGroup :menu="menu" :menu-entries="menuEntries" @add-group="handleAddGroup" />
                        </Modal>
                    </div>
                </div>

                <Container v-if="form.entries.length">
                    <VueDraggable ref="el" tag="ul" v-model="form.entries" class="gap-2 flex flex-col">
                        <Container
                            class="flex cursor-move justify-between"
                            v-for="(entry, index) in form.entries"
                            tag="li"
                            :key="JSON.stringify(entry)"
                        >
                            <!-- Link -->
                            <template v-if="entry['group_id'] !== undefined">
                                <div class="gap-2 font-medium flex items-center">
                                    <IconGrip />
                                    {{ entry.title }}
                                    <SlugCell :content="entry.url" />
                                </div>

                                <div class="flex items-center">
                                    <Action tag="button" variant="icon" @click="openModal(index)">
                                        <IconEdit />
                                        <span class="sr-only">Edit {{ entry.title }}</span>
                                    </Action>
                                    <Action tag="button" variant="icon" @click="removeLink(index)">
                                        <IconClose />
                                    </Action>
                                </div>
                            </template>

                            <!-- Group -->
                            <div class="gap-2 flex w-full cursor-move flex-col" v-else>
                                <div class="flex items-center justify-between">
                                    <div class="gap-2 font-medium flex items-center">
                                        <IconGrip />
                                        {{ entry.name }}
                                        <SlugCell :content="entry.slug" />
                                    </div>

                                    <div class="flex items-center">
                                        <Modal title="Add Children" variant="icon" size="2xl" position="left" icon="plus" v-model="childrenModal">
                                            <AsideChildren
                                                :menu="menu"
                                                :menu-entries="menuEntries"
                                                :group-id="entry.id"
                                                :group="entry"
                                                @add-children="handleAddChildren"
                                            />
                                        </Modal>
                                        <Action tag="button" variant="icon" @click="openEditGroup(index)">
                                            <IconEdit />
                                            <span class="sr-only">Edit {{ entry.title }}</span>
                                        </Action>
                                        <Action tag="button" variant="icon" @click="removeGroup(index)">
                                            <IconClose />
                                        </Action>
                                    </div>
                                </div>

                                <VueDraggable v-if="entry.links.length" tag="ul" v-model="entry.links" class="gap-2 flex flex-col">
                                    <Container
                                        tag="li"
                                        class="flex cursor-move items-center justify-between"
                                        v-for="(child, childIndex) in entry.links"
                                        :key="JSON.stringify(child)"
                                    >
                                        <div class="gap-2 font-medium flex items-center">
                                            <IconGrip />
                                            {{ child.title }}
                                            <SlugCell :content="child.url" />
                                        </div>

                                        <div class="flex items-center">
                                            <Action tag="button" variant="icon" @click="openChildGroup(index, childIndex)">
                                                <IconEdit />
                                                <span class="sr-only">Edit {{ child.title }}</span>
                                            </Action>
                                            <Action tag="button" variant="icon" @click="removeChild(index, childIndex)">
                                                <IconClose />
                                            </Action>
                                        </div>
                                    </Container>
                                </VueDraggable>
                            </div>
                        </Container>
                    </VueDraggable>
                </Container>

                <!-- Modal -->
                <GlobalModal v-model:is-open="isOpen" size="2xl" position="left" title="Edit Link">
                    <AsideEditLink :item="form.entries[linkIndex]" @close-modal="isOpen = false" @update-data="updateData" />
                </GlobalModal>

                <GlobalModal v-model:is-open="editGroupModal" size="2xl" position="left" title="Edit Group">
                    <AsideEditGroup :group="form.entries[linkIndex]" @close-modal="editGroupModal = false" @update-data="updateData" />
                </GlobalModal>

                <GlobalModal v-model:is-open="editChildModal" size="2xl" position="left" title="Edit Child">
                    <AsideEditLink
                        :item="form.entries[linkIndex].links[childIndex]"
                        @close-modal="editChildModal = false"
                        @update-data="updateChild"
                    />
                </GlobalModal>
            </section>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
