<script setup lang="ts">
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import { Menu } from '@/types/models/menu';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps<{
    menu: Menu;
    groupId?: number;
}>();

type Link = {
    title: string;
    url: string;
    blank: boolean;
};

const emit = defineEmits<{
    (e: 'addLink', link: Link): void;
}>();

const form = useForm({
    menu_id: props.menu.id,
    group_id: props.groupId ?? null,
    title: '',
    url: '',
    blank: false,
    order: props.menu.links.length,
});

const onSubmit = async () => {
    try {
        const response = await axios.post(route('admin.link.store'), form);

        emit('addLink', response.data);
    } catch (error) {
        if (error.response?.status === 422 && error.response.data?.errors) {
            form.setError(error.response.data.errors);
        } else {
            console.error('Unexpected error:', error);
        }
    }
};
</script>

<template>
    <form @submit.prevent="onSubmit" class="gap-2 flex flex-col">
        <pre>{{ form }}</pre>
        <InputLabel label="Title" name="title" v-model="form.title" :error="form.errors.title" />
        <InputLabel label="Url" name="url" v-model="form.url" :error="form.errors.url" />
        <ToggleInput label="Open in other tab" name="blank" value="_blank" v-model="form.blank" :error="form.errors.blank" />
        <Action tag="button" variant="primary">Add Custom Link</Action>
    </form>
</template>

<style scoped></style>
