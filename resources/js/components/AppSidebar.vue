<script setup lang="ts">
import { BookOpen, Folder, LayoutGrid, Palette, Settings, UserCog, Users, Wrench } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import NavFooter from '@/components/sidebar-nav/NavFooter.vue';
import NavMain from '@/components/sidebar-nav/NavMain.vue';
import NavUser from '@/components/sidebar-nav/NavUser.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'General',
        href: '#',
        items: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                route: 'dashboard.index',
                icon: LayoutGrid,
            },
        ],
    },
    {
        title: 'Other',
        href: '#',
        items: [
            {
                title: 'Settings',
                href: '/settings',
                icon: Settings,
                items: [
                    {
                        title: 'Profile',
                        href: '/settings/profile',
                        route: 'profile.edit',
                        icon: UserCog,
                    },
                    {
                        title: 'Password',
                        href: '/settings/password',
                        route: 'password.edit',
                        icon: Wrench,
                    },
                    {
                        title: 'Appearance',
                        href: '/settings/appearance',
                        route: 'appearance.index',
                        icon: Palette,
                    },
                    {
                        title: 'Users',
                        href: '/settings/users',
                        route: 'users.*',
                        isActive: ['roles.index', 'permissions.index'].includes(route().current() || ''),
                        icon: Users,
                    },
                ],
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/indra-yana/laravel12-starter.git',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://github.com/indra-yana/laravel12-starter?tab=readme-ov-file#introduction',
        icon: BookOpen,
    },
];
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
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
