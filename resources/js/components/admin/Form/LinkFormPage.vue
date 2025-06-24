<script setup lang="ts">
import { Form } from '@/types/models/form';
import { ref } from 'vue';
import CheckboxInput from '../Form/CheckboxInput.vue';
import Modal from '../Modal/Modal.vue';
import Container from '../Ui/Container.vue';

defineProps<{
    forms: Form[];
    form: any;
}>();

const formsModal = ref(false);

const formsToAdd = ref<Form[]>([]);

const emit = defineEmits<{
    (e: 'removeForm', index: number): void;
    (e: 'addForms', forms: Form[]): void;
}>();

const removeForm = (index: number) => {
    emit('removeForm', index);
};

const addForms = () => {
    if (formsToAdd.value.length) {
        emit('addForms', formsToAdd.value);

        formsToAdd.value = [];
        formsModal.value = false;
    }
};
</script>

<template>
    <Container class="gap-4 flex flex-col">
        <div class="flex items-center justify-between">
            <span class="font-medium">Link Forms</span>
            <Modal
                variant="outline"
                size="2xl"
                label="Add Forms"
                title="Add Forms"
                position="left"
                icon="plus"
                subtitle="Select forms to link with this page"
                v-model="formsModal"
            >
                <div class="gap-4 flex flex-col">
                    <ul class="gap-2 flex flex-col">
                        <li v-for="formItem in forms" :key="formItem.id" class="gap-2 flex items-center">
                            <CheckboxInput
                                :label="formItem.name"
                                :name="`formItem-${formItem.id}`"
                                :value="formItem"
                                v-model="formsToAdd"
                                transparent
                            />
                        </li>
                    </ul>

                    <Action tag="button" variant="primary" @click="addForms">Add Form(s)</Action>
                </div>
            </Modal>
        </div>

        <ul v-if="form.forms.length" class="gap-2 flex flex-col">
            <Container v-for="(formItem, index) in form.forms" :key="formItem.id" class="flex items-center justify-between">
                {{ formItem.name }}

                <Action tag="button" variant="icon" @click="removeForm(index)">
                    <span class="sr-only">Remove Form</span>
                    <IconClose />
                </Action>
            </Container>
        </ul>
    </Container>
</template>

<style scoped></style>
