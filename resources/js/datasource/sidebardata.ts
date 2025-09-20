import { NavItem } from "@/types";
import { BookOpen, Folder, LayoutGrid, Palette, Settings, UserCog, Users, Wrench } from 'lucide-vue-next';

export const mainNavItems: NavItem[] = [
    {
        title: 'General',
        href: '#',
        items: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                route: 'dashboard',
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
                        route: 'users.index',
                        isActive: ['roles.index', 'permissions.index'].includes(route().current() || ''),
                        icon: Users,
                    },
                ],
            },
        ],
    },
];

export const footerNavItems: NavItem[] = [
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

export const settingNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
        route: 'profile.edit',
    },
    {
        title: 'Password',
        href: '/settings/password',
        route: 'password.edit',
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
        route: 'appearance.index',
    },
    {
        title: 'Users',
        href: '/settings/users',
        route: 'users.*',
        isActive: ['roles.index', 'permissions.index'].includes(route().current() || ''),
    },
];