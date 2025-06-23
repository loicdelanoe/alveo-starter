<script setup lang="ts">
import { ref, watch } from 'vue';

import CheckboxInput from '@/components/admin/Form/CheckboxInput.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Dropdown from '@/components/admin/Ui/Dropdown.vue';
import Media from '@/components/admin/Ui/Media.vue';
import MediaDropdown from '@/components/admin/Ui/MediaDropdown.vue';
import TheLightbox from '@/components/admin/Ui/TheLightbox.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';

type Paths = {
    [key: string]: string;
};

export interface Media {
    id: number;
    name: string;
    path: Paths;
    type: string;
    metadata: MetaData;
}

export type MetaData = {
    width: number;
    height: number;
    alt: string;
};

const props = defineProps<{
    medias: Media[];
}>();

const isAllSelected = ref(false);

const isOpen = ref(false);

const lightbox = ref(false);
const mediaIndex = ref(0);

const openLightbox = (index: number) => {
    mediaIndex.value = index;

    lightbox.value = true;
};

const form = useForm({
    medias: [] as number[],
});

const bulkDelete = () => {
    if (form.medias.length === 0) {
        return;
    }

    form.medias.forEach((item) => {
        form.delete(route('admin.media.destroy', item));
    });

    form.medias = [];
    isAllSelected.value = false;
};

watch(isAllSelected, () => {
    if (isAllSelected.value) {
        form.medias = props.medias.map((item) => item.id);
    } else {
        form.medias = [];
    }
});
</script>

<template>
    <PanelLayout class="relative" title="Medias">
        <template #action>
            <Can permission="create medias">
                <Modal label="Upload Media" title="Choose a media" variant="primary" size="4xl" position="center" v-model="isOpen" icon="plus">
                    <MediaDropdown class="h-90 w-full" @close-modal="isOpen = false" />
                </Modal>
            </Can>
        </template>

        <div class="mb-4 flex">
            <CheckboxInput class="mr-2" label="Select all" name="select-all" v-model="isAllSelected" />
            <Dropdown class="ml-auto">
                <button
                    class="hover:bg-secondary-100 gap-2 rounded-md px-3.5 py-2 text-red-700 flex cursor-pointer items-center transition"
                    @click="bulkDelete"
                >
                    <IconTrash />
                    Delete item(s)
                </button>
            </Dropdown>
        </div>

        <MediaDropdown class="h-3/4" v-if="medias.length === 0" />

        <ul v-else class="sm:grid-cols-3 md:flex md:flex-wrap md:gap-2 grid grid-cols-2">
            <li v-for="(media, index) in medias" :key="media.id" class="md:max-w-[12rem] aspect-square w-full">
                <div
                    class="hover:after:bg-secondary-300/30 p-2 after:inset-0 after:rounded-lg relative after:pointer-events-none after:absolute after:transition after:content-['']"
                >
                    <Media class="rounded-lg relative z-2 aspect-square h-full w-full object-cover" :media="media" width="150" height="150" />
                    <!-- <input type="checkbox" name="`media-${media.id}`" :id="`media-${media.id}`" class="top-8 right-8 absolute z-4" /> -->
                    <CheckboxInput
                        class="top-3 right-5 absolute z-4"
                        :label="`media-${media.id}`"
                        :name="`media-${media.id}`"
                        :value="media.id"
                        v-model="form.medias"
                        hidden-label
                        transparent
                    />

                    <div class="flex cursor-pointer flex-col">
                        <span class="max-w-prose font-medium truncate text-ellipsis">{{ media.name }}</span>
                        <span class="max-w-prose text-sm truncate text-ellipsis">{{ media.type }}</span>
                    </div>

                    <button @click="openLightbox(index)" class="inset-0 absolute z-2 cursor-pointer">
                        <span class="sr-only"> View {{ media.name }}</span>
                    </button>
                </div>
            </li>
        </ul>
        <TheLightbox v-model:is-open="lightbox" v-model:media-index="mediaIndex" :medias="medias" />
    </PanelLayout>
</template>

<style scoped></style>
