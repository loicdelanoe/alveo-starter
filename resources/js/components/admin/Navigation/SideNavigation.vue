<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { useWindowSize } from '@vueuse/core';
import { computed } from 'vue';

import IconBlock from '@/components/admin/Icon/IconBlock.vue';
import IconClose from '@/components/admin/Icon/IconClose.vue';
import IconCollection from '@/components/admin/Icon/IconCollection.vue';
import IconDashboard from '@/components/admin/Icon/IconDashboard.vue';
import IconLogout from '@/components/admin/Icon/IconLogout.vue';
import IconMedia from '@/components/admin/Icon/IconMedia.vue';
import IconMenu from '@/components/admin/Icon/IconMenu.vue';
import IconPage from '@/components/admin/Icon/IconPage.vue';
import IconSettings from '@/components/admin/Icon/IconSettings.vue';
import IconUsers from '@/components/admin/Icon/IconUsers.vue';
import NavigationGroup from '@/components/admin/Navigation/NavigationGroup.vue';
import NavigationItem from '@/components/admin/Navigation/NavigationItem.vue';
import Can from '@/components/admin/Permission/Can.vue';
import CollapseButton from '@/components/admin/Ui/CollpaseButton.vue';
import { useSidebarStore } from '@/stores/sidebar';

import Alveo from '../Icon/Alveo.vue';
import IconForm from '../Icon/IconForm.vue';
import IconPlus from '../Icon/IconPlus.vue';
import IconSquareArrowRight from '../Icon/IconSquareArrowRight.vue';

const sidebar = useSidebarStore();
const page = usePage();
const { width } = useWindowSize();

const user = computed(() => page.props.auth.user);
</script>

<template>
    <aside
        class="sidebar bg-secondary-950 text-accent-50 top-0 bottom-0 w-68 gap-7 rounded-r-lg px-6 py-7 fixed z-10 flex h-dvh flex-col transition"
        :class="sidebar.classes"
    >
        <h2 class="sr-only">Side navigation</h2>
        <div class="gap-2 flex items-center">
            <Alveo class="fill-accent-400 w-28" />
            <!-- <img src="/images/alveo-logo.svg" alt="Logo" class="" /> -->
            <!-- <div class="w-8 h-8 bg-accent-300 rounded-md"></div> -->
            <!-- <h2 class="text-xl ">Site Name</h2> -->
            <button v-if="width < 768" @click="sidebar.toggle" class="ml-auto cursor-pointer">
                <span class="sr-only">Close</span>
                <IconClose />
            </button>
        </div>
        <nav class="no-scrollbar gap-6 flex h-full w-full flex-col overflow-auto">
            <h3 class="sr-only">Navigation</h3>
            <a class="gap-3 flex items-center hover:underline" target="_blank" href="/">
                Go to website
                <IconSquareArrowRight />
            </a>

            <NavigationGroup title="Main Navigation">
                <li>
                    <NavigationItem :href="route('admin.dashboard')">
                        <IconDashboard />
                        <span>Dashboard</span>
                    </NavigationItem>
                </li>
                <Can permission="show medias">
                    <li>
                        <NavigationItem :href="route('admin.media.index')">
                            <IconMedia />
                            <span>Medias</span>
                        </NavigationItem>
                    </li>
                </Can>
                <Can permission="show menus">
                    <li>
                        <NavigationItem :href="route('admin.menu.index')">
                            <IconMenu />
                            <span>Menus</span>
                        </NavigationItem>
                    </li>
                </Can>
                <Can permission="show users">
                    <li>
                        <NavigationItem :href="route('admin.user.index')">
                            <IconUsers />
                            <span>Users</span>
                        </NavigationItem>
                    </li>
                </Can>
            </NavigationGroup>

            <NavigationGroup title="Content">
                <Can permission="show pages">
                    <li>
                        <NavigationItem :href="route('admin.page.index')">
                            <IconPage />
                            <span>Pages</span>
                        </NavigationItem>
                    </li>
                </Can>
                <li>
                    <NavigationItem :href="route('admin.form.index')">
                        <IconForm />
                        <span>Forms</span>
                    </NavigationItem>
                </li>
                <Can permission="show collections">
                    <li>
                        <CollapseButton label="Collections" has-create>
                            <template #icon>
                                <IconCollection />
                            </template>

                            <template #items>
                                <li v-for="collectionType in page.props.navigation.collectionTypes">
                                    <NavigationItem :href="route('admin.collection-type.show', collectionType.type)" class="small">
                                        {{ collectionType.name }}
                                    </NavigationItem>
                                </li>
                            </template>
                        </CollapseButton>
                    </li>

                    <li class="mt-3">
                        <Can permission="create collections">
                            <Action tag="Link" variant="secondary" class="w-full" size="small" :href="route('admin.collection-type.create')">
                                <IconPlus />
                                Create Collection
                            </Action>
                        </Can>
                    </li>
                </Can>

                <Can permission="show blocks">
                    <li>
                        <CollapseButton label="Blocks" has-create>
                            <template #icon>
                                <IconBlock />
                            </template>

                            <template #items>
                                <li v-for="blockType in page.props.navigation.blockTypes">
                                    <NavigationItem :href="route('admin.block-type.show', blockType.type)" class="small">
                                        {{ blockType.name }}
                                    </NavigationItem>
                                </li>
                            </template>
                        </CollapseButton>
                    </li>

                    <li class="mt-3">
                        <Can permission="create blocks">
                            <Action tag="Link" variant="secondary" class="w-full" size="small" :href="route('admin.block-type.create')">
                                <IconPlus />
                                Create Block
                            </Action>
                        </Can>
                    </li>
                </Can>
            </NavigationGroup>

            <NavigationGroup title="System">
                <li>
                    <NavigationItem :href="route('admin.setting.index')">
                        <IconSettings />
                        <span>Settings</span>
                    </NavigationItem>
                </li>
            </NavigationGroup>
        </nav>

        <div class="gap-2.5 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="font-medium">{{ user.name }}</span>
                <span class="text-sm">{{ user.email }}</span>
            </div>
            <Action tag="Link" variant="primary icon" :href="route('logout')" method="post">
                <span class="sr-only">Logout</span>
                <IconLogout />
            </Action>
        </div>
    </aside>
</template>

<style scoped></style>
