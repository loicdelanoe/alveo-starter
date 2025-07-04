<script setup lang="ts">
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    item: any;
}>();

const emit = defineEmits<{
    (e: 'closeModal'): void;
    (e: 'updateData', item: any): void;
}>();

const form = useForm({
    title: props.item.title ?? props.item.name,
    url: props.item.url ?? props.item.slug,
    blank: props.item.blank ?? false,
});

const onSubmit = () => {
    form.patch(route('admin.link.update', props.item.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit('updateData', form.data());

            form.reset();

            emit('closeModal');
        },
    });
};
</script>

<template>
    <form @submit.prevent="onSubmit" class="gap-2 flex flex-col">
        <InputLabel label="Title" name="title" v-model="form.title" :error="form.errors.title" />
        <InputLabel label="Url" name="url" v-model="form.url" :error="form.errors.url" />
        <ToggleInput label="Open in other tab" name="blank" value="_blank" v-model="form.blank" :error="form.errors.blank" />
        <Action tag="button" variant="primary">Save Custom Link</Action>
    </form>
</template>

<style scoped></style>
