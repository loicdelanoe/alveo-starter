<script setup lang="ts">
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    group: any;
}>();

const emit = defineEmits<{
    (e: 'closeModal'): void;
    (e: 'updateData', item: any): void;
}>();

const form = useForm({
    name: props.group.name,
    slug: props.group.slug,
});

const onSubmit = () => {
    form.patch(route('admin.group.update', props.group.id), {
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
        <InputLabel label="Name" name="name" v-model="form.name" :error="form.errors.name" />
        <InputLabel label="Slug" name="slug" v-model="form.slug" :error="form.errors.slug" />
        <Action tag="button" variant="primary">Save Group</Action>
    </form>
</template>

<style scoped></style>
