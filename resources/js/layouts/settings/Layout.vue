<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link, usePage } from '@inertiajs/vue3';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import Heading from '@/components/Heading.vue';
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue';
import ScrollBar from '@/components/ui/scroll-area/ScrollBar.vue';
import useCan from '@/composables/useCan';

const { canAny } = useCan();

const sidebarNavItems: NavItem[] = [
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

const page = usePage();
const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy?.location).pathname : '';

</script>

<template>
    <div class="px-4 py-6">
        <Heading title="Settings" description="Manage your profile and account settings" />

        <div class="flex flex-col md:space-y-0 lg:flex-row lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <ScrollArea type='auto' orientation="horizontal" className='bg-background w-full min-w-40 px-1 py-2 md:block'>
                    <nav class="flex lg:flex-col space-x-0 space-y-1">
                        <template v-for="item in sidebarNavItems" :key="item.href">
                            <Button v-if="canAny(item.route!!)" variant="ghost" :class="['justify-start', { 'bg-muted': currentPath === item.href || item.isActive }]" as-child>
                                <Link :href="item.href">
                                {{ item.title }}
                                </Link>
                            </Button>
                        </template>
                    </nav>
                    <ScrollBar type='always' orientation="horizontal" />
                </ScrollArea>
            </aside>

            <Separator class="my-2 lg:hidden" />

            <div class="flex-1 mt-2">
                <section class="space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
