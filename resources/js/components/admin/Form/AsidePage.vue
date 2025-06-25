<script setup lang="ts">
import { Page } from '@/types/models/page';
import { ref } from 'vue';
import Container from '../Ui/Container.vue';
import CheckboxInput from './CheckboxInput.vue';

defineProps<{
    pages: Page[];
}>();

const emit = defineEmits<{
    (e: 'addPages', pages: Page[]): void;
}>();

const pagesToAdd = ref([]);

const onSubmit = () => {
    emit('addPages', pagesToAdd.value);
};
</script>

<template>
    <div class="gap-4 flex flex-col">
        <ul class="gap-2 flex flex-col">
            <Container tag="li" v-for="page in pages" :key="page.id">
                <CheckboxInput :label="page.title" :name="page.slug" :value="page" v-model="pagesToAdd" />
            </Container>
        </ul>
        <Action tag="button" variant="primary" @click="onSubmit">Add Page(s)</Action>
    </div>
</template>

<style scoped></style>
