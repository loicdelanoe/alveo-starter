<script setup lang="ts">
import Action from '@/components/admin/Action.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import IconCheck from '@/components/admin/Icon/IconCheck.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

const form = useForm({
    site_name: page.props.settings.site_name || '',
    site_description: page.props.settings.site_description || '',
    favicon: page.props.settings.favicon || '',
});

const onSubmit = () => {
    form.patch(route('admin.setting.update'));
};
</script>

<template>
    <PanelLayout title="Settings">
        <template #action>
            <Action tag="button" variant="primary" @click="onSubmit">
                <IconCheck />
                Save
            </Action>
        </template>

        <section class="mb-4">
            <h3 class="sr-only">Site name</h3>
            <InputLabel label="Site name" name="site-name" v-model="form.site_name" />
        </section>

        <section class="mb-4">
            <h3 class="sr-only">Site description</h3>
            <TextAreaInput label="Site description" name="site-description" v-model="form.site_description" />
        </section>

        <!-- <section>
            <h3 class="mb-2 text-2xl font-medium">Favicon</h3>
            <span class="text-sm">Upload a favicon for your site.</span>
            <img src="/favicon.svg" />
        </section> -->
    </PanelLayout>
</template>

<style scoped></style>
