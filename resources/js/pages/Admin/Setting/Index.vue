<script setup lang="ts">
import Action from '@/components/admin/Action.vue';
import FileInput from '@/components/admin/Form/FileInput.vue';
import InputLabel from '@/components/admin/Form/InputLabel.vue';
import TextAreaInput from '@/components/admin/Form/TextAreaInput.vue';
import IconCheck from '@/components/admin/Icon/IconCheck.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import Container from '@/components/admin/Ui/Container.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

const form = useForm({
    site_name: page.props.settings.site_name || '',
    site_description: page.props.settings.site_description || '',
    favicon: null,
});

const onSubmit = () => {
    form.post(route('admin.setting.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <PanelLayout title="Settings">
        <template #action>
            <span v-if="form.isDirty" class="font-medium text-orange-600 italic"> Form has unsaved changes </span>
            <Action tag="button" variant="primary" @click="onSubmit">
                <IconCheck />
                Save
            </Action>
        </template>

        <Container>
            <section class="mb-4">
                <h3 class="sr-only">Site name</h3>
                <InputLabel
                    label="Site name"
                    name="site_name"
                    v-model="form.site_name"
                    hint="This is the name of your website"
                    :error="form.errors.site_name"
                />
            </section>

            <section class="mb-4">
                <h3 class="sr-only">Site description</h3>
                <TextAreaInput
                    label="Site description"
                    name="site_description"
                    v-model="form.site_description"
                    hint="Explain in a few words what is the purpose of your website"
                    :error="form.errors.site_description"
                />
            </section>

            <section class="gap-2 flex flex-col">
                <h3 class="font-medium">Favicon</h3>

                <div class="gap-4 border-secondary-300 rounded-lg py-2 px-4 flex items-center border">
                    <img :src="page.props.settings.favicon" class="w-12" />

                    <!-- Favicon preview -->
                    <div class="gap-4 bg-secondary-950 px-4 py-2 rounded-t-md flex w-full items-center">
                        <div class="gap-2 flex items-center">
                            <span class="bg-red-500 w-3 h-3 block rounded-full"></span>
                            <span class="bg-orange-300 w-3 h-3 block rounded-full"></span>
                            <span class="bg-green-500 w-3 h-3 block rounded-full"></span>
                        </div>
                        <div class="bg-white/20 py-2 px-3 rounded-md gap-2 text-white w-52 flex items-center">
                            <img :src="page.props.settings.favicon" class="w-4" />
                            <span>{{ page.props.settings.site_name }}</span>
                            <IconClose class="ml-auto" />
                        </div>
                    </div>
                </div>
                <FileInput
                    label="Favicon"
                    name="favicon"
                    type="file"
                    hidden-label
                    v-model="form.favicon"
                    :error="form.errors.favicon"
                    hint="The favicon is what you see in browser tabs, bookmark bars"
                />
            </section>
        </Container>
    </PanelLayout>
</template>

<style scoped></style>
