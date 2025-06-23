<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import { ExtendedPageProps } from '@/components/admin/Form/AsideField.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import Block from '@/components/admin/Form/Page/Block.vue';
import MultiBlockAdd from '@/components/admin/Form/Page/MultiBlockAdd.vue';
import MultiBlockCreate from '@/components/admin/Form/Page/MultiBlockCreate.vue';
import SelectInput from '@/components/admin/Form/SelectInput.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconCheck from '@/components/admin/Icon/IconCheck.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Can from '@/components/admin/Permission/Can.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import TabsHead from '@/components/admin/Ui/TabsHead.vue';
import VisitLink from '@/components/admin/Ui/VisitLink.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Page } from '@/types/models/page';
import { deleteItem } from '@/utils/utils';

import type { BlockType } from '@/Pages/Admin/Block/Create.vue';
import type { Block as TBlock } from '@/types/models/block';

const { page, blocks } = defineProps<{
    page: Page;
    blocks: TBlock[];
    blockTypes: BlockType[];
}>();

const pageProps = usePage<ExtendedPageProps>().props;

export type PageForm = {
    status: string;
    title: string;
    slug: string;
    meta_title: string;
    meta_description: string;
    blocks: TBlock[];
    [key: string]: any;
};

const draggedIndex = ref<number | null>(null);

const form = useForm<PageForm>({
    status: page.status,
    title: page.title,
    slug: page.slug,
    meta_title: page.meta_title,
    meta_description: page.meta_description,
    og_type: page.og_type,
    blocks: page.blocks as TBlock[],
    is_archive: page.is_archive,
    collection_type_id: page.collection_type_id || '',
});

const status = ['draft', 'published'];

const onSubmit = () => {
    form.patch(route('admin.page.update', page.id), {
        preserveScroll: true,
        // preserveState: false,
    });
};

const isHome = page.is_home;

const createBlockModal = ref(false);
const existingBlockModal = ref(false);

const tab = ref(0);
const tabs = [
    { name: 'General', id: 0 },
    { name: 'SEO', id: 1 },
    { name: 'Settings', id: 2 },
];

const links = [
    {
        label: 'Pages',
        href: route('admin.page.index'),
    },
    {
        label: page.title,
        href: route('admin.page.show', page.slug),
    },
];

const collectionTypesOptions = Object.keys(pageProps.collectionTypes).map((key) => ({
    label: key,
    value: pageProps.collectionTypes[key],
}));
</script>

<template>
    <PanelLayout :title="page.title" :subtitle="page.title">
        <template #breadcrumbs>
            <Breadcrumbs :links="links" />
        </template>

        <template #action>
            <Can permission="delete pages">
                <Action
                    tag="button"
                    :variant="['delete', 'icon']"
                    @click="deleteItem(route('admin.page.destroy', page.slug), 'Are you sure you want to delete this page?', form)"
                >
                    <IconTrash />
                </Action>
            </Can>
        </template>
        <div v-if="isHome" class="mb-4">
            <p class="italic opacity-50">Define as homepage</p>
        </div>

        <pre>{{ form.errors }}</pre>

        <div class="gap-5 lg:grid-cols-3 relative grid grid-cols-1">
            <div class="lg:col-span-2 flex flex-col">
                <TabsHead :tabs="tabs" v-model="tab" />
                <!-- General -->
                <!-- Title and Slug -->
                <div v-show="tab === 0" class="gap-6 flex w-full flex-col">
                    <Container tag="section" class="gap-4 md:flex-row flex flex-col">
                        <h3 class="sr-only">General</h3>
                        <InputLabel label="Title" name="title" type="text" v-model="form.title" :error="form.errors.title" />
                        <div class="w-full">
                            <InputLabel label="Slug" name="slug" type="text" v-model="form.slug" :disabled="isHome" :error="form.errors.slug" />
                        </div>
                    </Container>

                    <!-- Blocks -->
                    <section>
                        <h3 class="mb-2 text-2xl font-medium">Blocks</h3>
                        <div class="mb-4 gap-4 flex flex-col">
                            <template v-for="(block, index) in form.blocks" :key="block.id">
                                <Block
                                    :index="index"
                                    :block-type="blockTypes.find((blockType) => blockType.id === block.block_type_id)!"
                                    :block="block"
                                    :page-form="form"
                                    v-model:dragged="draggedIndex"
                                />
                            </template>
                        </div>
                        <div class="gap-4 flex">
                            <Can permission="create blocks">
                                <Modal
                                    variant="primary"
                                    size="2xl"
                                    label="Create block"
                                    title="Create block"
                                    position="left"
                                    v-model="createBlockModal"
                                >
                                    <MultiBlockCreate :block-types="blockTypes" :page-form="form" @close-modal="createBlockModal = false" />
                                </Modal>
                                <Modal
                                    variant="outline"
                                    size="2xl"
                                    label="Add existing block"
                                    title="Add existing block"
                                    position="left"
                                    v-model="existingBlockModal"
                                >
                                    <MultiBlockAdd :block-types="blockTypes" :page-form="form" @close-modal="existingBlockModal = false" />
                                </Modal>
                            </Can>
                        </div>
                    </section>

                    <!-- API Response -->
                    <!-- <section>
            <h3 class="mb-2 text-2xl font-medium">API response</h3>
            <pre class="bg-secondary-950 text-white p-5 rounded-lg overflow-auto w-full shadow">{{
              form
            }}</pre>
          </section> -->
                </div>

                <!-- SEO -->
                <Container v-show="tab === 1" tag="section" class="gap-4 flex flex-col">
                    <h3 class="text-xl font-medium sr-only">SEO</h3>
                    <InputLabel label="Meta title" name="meta_title" type="text" v-model="form.meta_title" :error="form.errors.meta_title" />
                    <TextAreaInput
                        label="Meta description"
                        name="meta_description"
                        type="text"
                        v-model="form.meta_description"
                        :error="form.errors.meta_description"
                    />
                    <InputLabel label="Open Graph type" name="og_type" type="text" v-model="form.og_type" :error="form.errors.og_type" />
                </Container>

                <!-- Settings -->
                <section v-show="tab === 2" class="gap-4 flex flex-col">
                    <h3 class="sr-only">Settings</h3>
                    <Container>
                        <ToggleInput label="Define as archive page" name="is_archive" v-model="form.is_archive" reverse />
                        <SelectInput
                            v-if="form.is_archive"
                            label="Collection Type"
                            name="collection_type_id"
                            v-model="form.collection_type_id"
                            :options="collectionTypesOptions"
                            class="mt-4"
                        />
                    </Container>
                </section>
            </div>

            <!-- Status -->
            <Container tag="aside" class="gap-2 lg:sticky lg:top-7 flex w-full flex-col self-start">
                <h3 class="sr-only">Aside</h3>
                <SelectInput name="status" label="Status" :options="status" v-model="form.status" :error="form.errors.status" />
                <Can permission="edit pages">
                    <p v-if="form.isDirty" class="font-medium text-orange-600 italic">Form has unsaved changes</p>
                    <Action tag="button" @click="onSubmit" variant="primary">
                        <IconCheck />
                        Save
                    </Action>
                    <VisitLink class="mx-auto" :href="route('page.show', page.slug)" :is-home="page.is_home" />
                </Can>
            </Container>
        </div>
    </PanelLayout>
</template>

<style scoped></style>
