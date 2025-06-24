<script setup lang="ts">
import PanelLayout from '@/Layouts/PanelLayout.vue';
</script>

<template>
    <PanelLayout title="Forms">
        <template #action>
            <Action tag="Link" variant="primary" size="normal" :href="route('admin.form.create')">
                    <IconPlus />
                    Create Page
                </Action>
        </template>
    </PanelLayout>
</template>

<style scoped></style>
