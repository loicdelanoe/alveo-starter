<script setup lang="ts">
import { ref } from 'vue';

import Modal from '@/components/admin/Modal/Modal.vue';
import Media from '@/components/admin/Ui/Media.vue';
import MediaDropdown from '@/components/admin/Ui/MediaDropdown.vue';
import TheLightbox from '@/components/admin/Ui/TheLightbox.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import MediaCreate from '@/Pages/Admin/Media/Create.vue';

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

defineProps<{
    medias: Media[];
}>();

const isOpen = ref(false);

const lightbox = ref(false);
const mediaIndex = ref(0);

const openLightbox = (index: number) => {
    mediaIndex.value = index;

    lightbox.value = true;
};
</script>

<template>
    <PanelLayout class="relative" title="Medias">
        <template #action>
            <Can permission="create medias">
                <Modal label="Upload Media" title="Choose a media" variant="primary" size="4xl" position="center" v-model="isOpen" icon="plus">
                    <MediaCreate @close-modal="isOpen = false" />
                </Modal>
            </Can>
        </template>

        <MediaDropdown class="h-3/4" v-if="medias.length === 0" />

        <ul v-else class="sm:grid-cols-3 md:flex md:flex-wrap md:gap-2 grid grid-cols-2">
            <li v-for="(media, index) in medias" :key="media.id" class="md:max-w-[12rem] aspect-square w-full">
                <div
                    class="hover:after:bg-secondary-300/30 p-2 after:inset-0 after:rounded-lg relative after:pointer-events-none after:absolute after:transition after:content-['']"
                >
                    <Media class="rounded-lg relative z-2 aspect-square h-full w-full object-cover" :media="media" width="150" height="150" />

                    <div class="flex cursor-pointer flex-col">
                        <span class="max-w-prose font-medium truncate text-ellipsis">{{ media.name }}</span>
                        <span class="max-w-prose text-sm truncate text-ellipsis">{{ media.type }}</span>
                    </div>

                    <button @click="openLightbox(index)" class="inset-0 absolute z-3 cursor-pointer">
                        <span class="sr-only"> View {{ media.name }}</span>
                    </button>
                </div>
            </li>
        </ul>
        <TheLightbox v-model:is-open="lightbox" v-model:media-index="mediaIndex" :medias="medias" />
    </PanelLayout>
</template>

<style scoped></style>
