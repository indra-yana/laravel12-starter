<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, useSidebar } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import SidebarMenuLink from './SidebarMenuLink.vue';
import SidebarMenuCollapsible from './SidebarMenuCollapsible.vue';
import SidebarMenuCollapsedDropdown from './SidebarMenuCollapsedDropdown.vue';

defineProps<{
    items: NavItem[];
}>();

usePage<SharedData>();
const { state, isMobile } = useSidebar();

</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <template v-for="item in items" :key="item.title">
            <SidebarGroupLabel :class="{ 'hidden': state === 'collapsed' }">{{ item.title }}</SidebarGroupLabel>
            <SidebarMenu>
                <template v-for="subitem in item.items">

                    <SidebarMenuLink v-if="!subitem.items" :item="subitem" :href="subitem.href" />

                    <SidebarMenuCollapsedDropdown v-else-if="state === 'collapsed' && !isMobile" :item="subitem" :href="subitem.href" />

                    <SidebarMenuCollapsible v-else :item="subitem" :href="subitem.href" />

                    <!-- <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton 
                        as-child :is-active="item.href === page.url"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem> -->
                </template>
            </SidebarMenu>
        </template>
    </SidebarGroup>
</template>
