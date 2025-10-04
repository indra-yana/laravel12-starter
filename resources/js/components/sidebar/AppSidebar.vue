<script setup lang="ts">
import { footerNavItems, mainNavItems } from '@/datasource/sidebardata';
import { Link, usePage } from '@inertiajs/vue3';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/sidebar/NavFooter.vue';
import NavMain from '@/components/sidebar/NavMain.vue';
import NavUser from '@/components/sidebar/NavUser.vue';
import { computed } from 'vue';
import { SharedData } from '@/types';

const page = usePage<SharedData>()
const dynamicMenus = page.props.menus || [];
const navItems = computed(() => [...mainNavItems, ...dynamicMenus])

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <SidebarMenu>
                <SidebarMenuItem>
                    <NavUser />
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
