<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, useSidebar } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import SidebarMenuCollapsedDropdown from './SidebarMenuCollapsedDropdown.vue';
import SidebarMenuCollapsible from './SidebarMenuCollapsible.vue';
import SidebarMenuLink from './SidebarMenuLink.vue';
import useMenuPermissions from '@/composables/useMenuPermissions';

defineProps<{
    items: NavItem[];
}>();

usePage<SharedData>();
const { state, isMobile } = useSidebar();
const { hasPermissionForGroup } = useMenuPermissions();

</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <template v-for="item in items" :key="item.title">
            <SidebarGroupLabel v-if="hasPermissionForGroup(item) || item.title == 'Ungrouped' || item.enable_permission == false" :class="{ 'hidden': state === 'collapsed' }">{{ trans(item.title) }}</SidebarGroupLabel>
            <SidebarMenu>
                <template v-for="subitem in item.items">
                    <SidebarMenuLink v-if="!subitem.items" :item="subitem" :href="subitem.href" />
                    <SidebarMenuCollapsedDropdown v-else-if="state === 'collapsed' && !isMobile" :item="subitem" :href="item.href" />
                    <SidebarMenuCollapsible v-else :item="subitem" :href="subitem.href" />
                </template>
            </SidebarMenu>
        </template>
    </SidebarGroup>
</template>
