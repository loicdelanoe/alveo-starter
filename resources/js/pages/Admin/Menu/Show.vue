<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import InputLabel from '@/components/admin/Form/InputLabel.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { deleteItem } from '@/utils/utils';
import { VueDraggable } from 'vue-draggable-plus';

import AsideChildren from '@/components/admin/Form/Aside/AsideChildren.vue';
import AsideGroup from '@/components/admin/Form/Aside/AsideGroup.vue';
import AsideLink from '@/components/admin/Form/Aside/AsideLink.vue';
import IconGrip from '@/components/admin/Icon/IconGrip.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import SlugCell from '@/components/admin/Ui/Table/SlugCell.vue';
import type { Menu } from '@/types/models/menu';
import type { Page } from '@/types/models/page';
import { ref } from 'vue';

const props = defineProps<{
    menu: Menu;
    menuEntries: any[];
    pages: Page[];
}>();

const linkModal = ref(false);
const groupModal = ref(false);
const childrenModal = ref(false);

const form = useForm({
    name: props.menu.name,
    slug: props.menu.slug,
    active: props.menu.active,
    entries: props.menuEntries ?? [],
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
    form.entries.push({ ...group });

    groupModal.value = false;
};

const handleAddChildren = (link: { title: string; url: string; blank: boolean; group_id: number }, groupId: number) => {
    console.log('Adding children link:', link, 'to group ID:', groupId);

    form.entries[form.entries.findIndex((entry) => entry.group_id === groupId)].push({
        ...link,
    });

    childrenModal.value = false;
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

        <pre>{{ menuEntries }}</pre>

        <div class="gap-6 flex flex-col">
            <Container class="gap-4 md:flex-row md:gap-6 flex flex-col">
                <InputLabel label="Name" name="name" type="text" v-model="form.name" :error="form.errors.name" />
                <InputLabel label="Slug" name="slug" type="text" v-model="form.slug" :error="form.errors.slug" hint="A unique identifier" />
            </Container>

            <!-- Entries -->
            <section>
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

                <Container>
                    <VueDraggable ref="el" tag="ul" v-model="form.entries" class="gap-2 flex flex-col">
                        <Container class="flex justify-between" v-for="entry in form.entries" tag="li" :key="JSON.stringify(entry)">
                            <template v-if="entry['group_id'] !== undefined">
                                <div class="gap-2 flex items-center">
                                    <IconGrip />
                                    {{ entry.title }}
                                    <SlugCell :content="entry.url" />
                                </div>
                            </template>
                            <div class="gap-2 flex w-full flex-col" v-else>
                                <div class="flex items-center justify-between">
                                    <div class="gap-2 flex items-center">
                                        <IconGrip />
                                        {{ entry.name }}
                                        {{ entry.id }}
                                    </div>

                                    <Modal
                                        label="Add Children"
                                        title="Add Children"
                                        variant="outline"
                                        size="2xl"
                                        position="left"
                                        v-model="childrenModal"
                                    >
                                        <AsideChildren
                                            :menu="menu"
                                            :menu-entries="menuEntries"
                                            :group-id="entry.id"
                                            @add-children="handleAddChildren"
                                        />
                                    </Modal>
                                </div>

                                <ul>
                                    <Container tag="li" class="flex" v-for="child in entry.links" :key="JSON.stringify(child)">
                                        {{ child.title }}
                                    </Container>
                                </ul>
                            </div>
                        </Container>
                    </VueDraggable>
                </Container>
            </section>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
