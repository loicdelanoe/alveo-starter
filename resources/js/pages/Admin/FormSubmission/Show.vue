<script setup lang="ts">
import IconTrash from '@/components/admin/Icon/IconTrash.vue';
import Breadcrumbs from '@/components/admin/Ui/Breadcrumbs.vue';
import Container from '@/components/admin/Ui/Container.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Form } from '@/stores/field';
import { deleteItem } from '@/utils/utils';
import { capitalize } from 'vue';

const props = defineProps<{
    form: Form;
    submission: any;
}>();

const links = [
    {
        label: 'Forms',
        href: route('admin.form.index'),
    },
    {
        label: props.form.name,
        href: route('admin.form.submissions.index', props.form.slug),
    },
    {
        label: props.submission.id,
        href: route('admin.form.submissions.show', [props.form.slug, props.submission.id]),
    },
];
</script>

<template>
    <PanelLayout :title="form.name">
        <template #breadcrumbs>
            <Breadcrumbs :links="links" />
        </template>
        <template #action>
            <Action
                tag="button"
                :variant="['delete', 'icon']"
                @click="
                    deleteItem(
                        route('admin.form.submissions.destroy', [form.slug, submission.id]),
                        'Are you sure you want to delete this submission?',
                    )
                "
            >
                <IconTrash />
            </Action>
        </template>
        <section>
            <h3 class="mb-2 text-2xl font-medium">Content</h3>
            <Container>
                <dl class="gap-4 flex flex-col">
                    <div class="gap-1 md:gap-4 md:flex-row flex flex-col" v-for="(value, key) in submission.data" :key="JSON.stringify(value)">
                        <dt class="font-medium min-w-0 w-1/8">{{ capitalize(key) }}:</dt>
                        <dd class="md:ml-4 flex-1">{{ value }}</dd>
                    </div>
                </dl>
            </Container>
        </section>
    </PanelLayout>
</template>

<style scoped></style>
