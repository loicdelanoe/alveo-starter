<script setup lang="ts">
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import { Menu } from '@/types/models/menu';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps<{
    menu: Menu;
    menuEntries: any[];
}>();

type Group = {
    name: string;
    slug: string;
};

const emit = defineEmits<{
    (e: 'addGroup', group: Group): void;
}>();

const form = useForm({
    menu_id: props.menu.id,
    name: '',
    slug: '',
    order: props.menuEntries.length,
});

const onSubmit = async () => {
    try {
        const response = await axios.post(route('admin.group.store'), form);

        emit('addGroup', response.data);
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
        <InputLabel label="Name" name="name" v-model="form.name" :error="form.errors.name" />
        <InputLabel label="Slug" name="slug" v-model="form.slug" :error="form.errors.slug" />
        <Action tag="button" variant="primary">Add Custom Link</Action>
    </form>
</template>

<style scoped></style>
