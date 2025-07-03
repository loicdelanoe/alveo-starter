<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits<{
    (e: 'closeModal'): void;
}>();

const form = useForm({
    title: '',
    url: '',
    blank: false,
});

const onSubmit = () => {
    emit('closeModal');
};
</script>

<template>
    <form @submit.prevent="onSubmit" class="gap-2 flex flex-col">
        <InputLabel label="Title" name="title" v-model="form.title" :error="form.errors.title" />
        <InputLabel label="Url" name="url" v-model="form.url" :error="form.errors.url" />
        <ToggleInput label="Open in other tab" name="blank" value="_blank" v-model="form.blank" :error="form.errors.blank" />
        <Action tag="button" variant="primary">Add Custom Link</Action>
    </form>
</template>

<style scoped></style>
