<script setup lang="ts">
import Tab from '@/components/admin/Ui/Tab.vue';

defineProps<{
    tabs: { name: string; id: number }[];
}>();

const model = defineModel();
</script>

<template>
    <ul class="border-secondary-300 mb-6 flex w-full border-b">
        <li v-for="tab in tabs" :key="tab.id" class="w-full max-w-32">
            <Tab @click="model = tab.id" :active="model === tab.id">{{ tab.name }}</Tab>
        </li>
    </ul>
</template>

<style scoped></style>
