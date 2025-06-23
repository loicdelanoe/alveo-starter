<script setup lang="ts">
import axios from 'axios';
import { computed, ref } from 'vue';

import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';
import Modal from '@/components/admin/Modal/Modal.vue';
import Media from '@/components/admin/Ui/Media.vue';
import MediaCreate from '@/Pages/Admin/Media/Create.vue';

import type { Media as TMedia } from '@/Pages/Admin/Media/Index.vue';

defineProps<{
    label: string;
    name: string;
    error?: string;
    required?: boolean;
    value?: string;
    hint?: string;
    disabled?: boolean;
}>();

const model = defineModel<string | null>({ default: null });

const medias = ref<TMedia[] | null>(null);
const selectedMedia = ref<string | null>(model.value || null);
const isOpen = ref(false);
const isUploadModalOpen = ref(false);
const errorMessage = ref('');

const hasSelectedMedia = computed(() => model.value && selectedMedia.value);

const fetchMedias = async () => {
    try {
        const response = await axios.get(route('admin.media.index'));

        // Filter only images via media type
        medias.value = response.data.filter((media: TMedia) => media.type.startsWith('image/'));
    } catch (e) {
        errorMessage.value = 'Failed to load media. Please try again later.';
        console.error('Media fetch error:', e);
    }
};

const submitMedia = () => {
    if (selectedMedia.value) {
        model.value = selectedMedia.value;
    }

    isOpen.value = false;
};

const reloadMedias = () => {
    isUploadModalOpen.value = false;

    fetchMedias();
};
</script>

<template>
    <div class="flex w-full flex-col gap-2">
        <label :for="name" class="text-md pl-1.5 font-medium">{{ label }}</label>
        <template v-if="hasSelectedMedia">
            <Media
                v-if="selectedMedia && model"
                :media="selectedMedia?.toString()"
                :key="model"
                class="aspect-square h-[15rem] w-full rounded-lg object-cover"
            />
        </template>

        <Modal label="Select Media" title="Select Media" variant="primary" position="center" size="4xl" @click="fetchMedias" v-model="isOpen">
            <template #action>
                <Can permission="create medias">
                    <Modal
                        label="Upload Media"
                        title="Choose a media"
                        variant="primary"
                        size="xl"
                        position="center"
                        v-model="isUploadModalOpen"
                        icon="plus"
                    >
                        <MediaCreate @close-modal="reloadMedias" />
                    </Modal>
                </Can>
            </template>

            <div v-if="medias" class="flex flex-col gap-6">
                <ul class="flex flex-wrap gap-4">
                    <li
                        v-for="(media, index) in medias"
                        :key="media.id"
                        class="relative after:pointer-events-none after:absolute after:inset-0 after:z-10 after:rounded-lg after:transition after:content-[''] hover:after:bg-black/30"
                    >
                        <div class="relative aspect-square max-w-[12rem]">
                            <Media class="h-full w-full rounded-lg object-cover" :media="media" />
                        </div>
                        <input
                            type="radio"
                            class="absolute inset-0 cursor-pointer appearance-none rounded-lg bg-transparent"
                            :value="media.id"
                            v-model="selectedMedia"
                        />
                    </li>
                </ul>
            </div>

            <template #footer>
                <Action tag="button" variant="primary" v-if="selectedMedia" @click.prevent="submitMedia"> Submit </Action>
            </template>
        </Modal>
        <span v-if="hint" class="text-sm italic opacity-50">{{ hint }}</span>
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped>
@reference "../../../../css/admin/admin.css";

input[type='radio'] {
    @apply border-0 transition;
}

input[type='radio']:checked {
    @apply border-secondary-500 border-2;
}
</style>
