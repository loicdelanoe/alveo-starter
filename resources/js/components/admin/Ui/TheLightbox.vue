<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, watch } from 'vue';

import InputLabel from '@/components/admin/Form/InputLabel.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import IconChevronLeft from '@/components/admin/Icon/IconChevronLeft.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Media from '@/components/admin/Ui/Media.vue';
import { deleteItem } from '@/utils/utils';

import type { MetaData, Media as TMedia } from '@/Pages/Admin/Media/Index.vue';

const props = defineProps<{
    medias: TMedia[];
}>();

const mediaIndex = defineModel('mediaIndex', {
    default: 0,
});

const selectedMedia = computed<TMedia>(() => {
    return props.medias[mediaIndex.value];
});

const isOpen = defineModel('isOpen');
const form = useForm({
    name: '',
    //   name: selectedMedia.value.name ?? '',
    metadata: {} as MetaData,
});

const nextMedia = () => {
    if (mediaIndex.value < props.medias.length - 1) {
        mediaIndex.value++;
    } else {
        mediaIndex.value = 0;
    }
};

const onSubmit = () => {
    form.patch(route('admin.media.update', selectedMedia.value.id));
};

const previousMedia = () => {
    if (mediaIndex.value > 0) {
        mediaIndex.value--;
    } else {
        mediaIndex.value = props.medias.length - 1;
    }
};

const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
        isOpen.value = false;
    }
};

watch(isOpen, (newValue) => {
    if (newValue) {
        window.addEventListener('keydown', handleEscape);
    } else {
        window.removeEventListener('keydown', handleEscape);
    }
});

watch(selectedMedia, (newValue) => {
    form.name = newValue.name;
    form.metadata = newValue.metadata;
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleEscape);
});
</script>

<template>
    <Transition name="appear">
        <Teleport to="body">
            <div v-if="isOpen" class="fixed inset-0 z-20 flex items-center justify-center bg-black/50 md:p-5" @click.self="isOpen = false">
                <div class="flex h-full w-full flex-col md:flex-row md:gap-4">
                    <div class="relative flex h-full w-full">
                        <Action tag="button" variant="icon secondary" class="absolute top-2 right-2 md:top-0 md:right-0" @click="isOpen = false">
                            <span class="sr-only">Close lightbox</span>
                            <IconClose />
                        </Action>

                        <!-- Navigation -->
                        <div class="center-x absolute bottom-0 flex justify-center gap-1 rounded-lg max-sm:mb-2">
                            <Action tag="button" variant="primary" @click="previousMedia">
                                <IconChevronLeft />
                                <span class="sr-only">Previous</span>
                            </Action>
                            <Action tag="button" variant="primary" @click="nextMedia">
                                <span class="sr-only">Next</span>
                                <IconChevronLeft class="rotate-180" />
                            </Action>
                        </div>

                        <!-- Media -->
                        <Media
                            v-if="selectedMedia"
                            class="m-auto w-full max-w-2/3 rounded-lg"
                            :media="selectedMedia"
                            :key="selectedMedia.id"
                            controls
                        />
                    </div>

                    <!-- Aside -->
                    <div class="flex h-full w-full flex-col gap-4 rounded-lg bg-white p-6 md:w-[45%] md:overflow-auto">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-medium">Informations</h3>
                        </div>
                        <InputLabel label="Name" type="text" name="name" v-model="form.name" />
                        <TextAreaInput
                            v-if="selectedMedia.metadata.alt"
                            label="Alternative text"
                            type="text"
                            name="alt"
                            v-model="form.metadata.alt"
                        />
                        <div class="mt-auto flex w-full gap-2">
                            <Can permission="edit medias">
                                <Action tag="button" variant="primary" class="w-full" @click="onSubmit"> Update </Action>
                            </Can>
                            <Can permission="delete medias">
                                <Action
                                    tag="button"
                                    :variant="['delete', 'icon']"
                                    @click="
                                        deleteItem(
                                            route('admin.media.destroy', selectedMedia.id),
                                            'Are you sure you want to delete this media?',
                                            form,
                                        )
                                    "
                                >
                                    <IconTrash />
                                </Action>
                            </Can>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </Transition>
</template>

<style scoped>
.appear-enter-active,
.appear-leave-active {
    transition: opacity 0.3s ease-out;
}

.appear-enter-from,
.appear-leave-to {
    opacity: 0;
}
</style>
