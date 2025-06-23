<script setup lang="ts">
import axios from 'axios';
import { ModelRef, ref } from 'vue';

import Modal from '@/components/admin/Modal/Modal.vue';
import MediaCreate from '@/Pages/Admin/Media/Create.vue';

import Action from '../Action.vue';
import IconCheck from '../Icon/IconCheck.vue';
import Media from '../Ui/Media.vue';

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

const model = defineModel({ default: [] }) as ModelRef<string | null>;
const medias = ref<TMedia[] | null>(null);

const fetchMedia = async () => {
    try {
        const response = await axios.get(route('admin.media.index'));

        medias.value = response.data;
    } catch (e) {
        console.error(e);
    }
};

// const selectedMedia = ref<string | null>(model.value || null)
const isOpen = ref(false);
const uploadModal = ref(false);

const submitMedia = () => {
    if (model.value) {
        model.value = model.value;
    }

    isOpen.value = false;
};

const reloadMedias = () => {
    uploadModal.value = false;

    fetchMedia();
};
</script>

<template>
    <div class="flex w-full flex-col gap-2">
        <label :for="name" class="text-md pl-1.5 font-medium">{{ label }}</label>
        <div class="flex flex-wrap gap-4">
            <template v-for="image in model" :key="JSON.stringify(image)">
                <Media class="aspect-square h-[13rem] w-[13rem] rounded-lg object-cover" v-if="image" :media="image.toString()" :key="image" />
            </template>
        </div>

        <Modal label="Select Media" title="Select Media" variant="primary" position="center" size="xl" @click="fetchMedia" v-model="isOpen">
            <template #action>
                <Modal label="Upload Media" title="Choose a media" variant="primary" size="xl" position="center" v-model="uploadModal" icon="plus">
                    <MediaCreate @close-modal="reloadMedias" />
                </Modal>
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
                            type="checkbox"
                            class="checked:border-accent-300 peer absolute inset-0 cursor-pointer appearance-none rounded-lg bg-transparent checked:border-2"
                            :value="media.id"
                            v-model="model"
                        />
                        <span
                            class="bg-accent-400 text-secondary-950 absolute top-2 right-2 inline-flex aspect-square items-center justify-center rounded-full opacity-0 transition peer-checked:opacity-100"
                        >
                            <IconCheck />
                        </span>
                    </li>
                </ul>
            </div>

            <template #footer>
                <Action tag="button" variant="primary" v-if="model" @click.prevent="submitMedia"> Submit </Action>
            </template>
        </Modal>
        <span v-if="hint" class="text-sm italic opacity-50">{{ hint }}</span>
        <span v-if="error" class="font-medium text-red-500">
            {{ Array.isArray(error) ? error[0] : error }}
        </span>
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
