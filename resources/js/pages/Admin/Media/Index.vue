<script setup lang="ts">
import { ref } from 'vue';

import Modal from '@/components/admin/Modal/Modal.vue';
import Media from '@/components/admin/Ui/Media.vue';
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

const props = defineProps<{
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

        <ul class="grid grid-cols-2 sm:grid-cols-3 md:flex md:flex-wrap md:gap-2">
            <li v-for="(media, index) in medias" :key="media.id" class="aspect-square w-full md:max-w-[12rem]">
                <div
                    class="hover:after:bg-secondary-300/30 relative p-2 after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:transition after:content-['']"
                >
                    <Media class="relative z-2 aspect-square h-full w-full rounded-lg object-cover" :media="media" width="150" height="150" />

                    <div class="flex cursor-pointer flex-col">
                        <span class="max-w-prose truncate font-medium text-ellipsis">{{ media.name }}</span>
                        <span class="max-w-prose truncate text-sm text-ellipsis">{{ media.type }}</span>
                    </div>

                    <button @click="openLightbox(index)" class="absolute inset-0 z-3 cursor-pointer">
                        <span class="sr-only"> View {{ media.name }}</span>
                    </button>
                </div>
            </li>
        </ul>
        <TheLightbox v-model:is-open="lightbox" v-model:media-index="mediaIndex" :medias="medias" />
    </PanelLayout>
</template>

<style scoped></style>
