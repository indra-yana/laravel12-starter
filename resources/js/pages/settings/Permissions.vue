<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { User, type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import PermissionManager, { Permission } from '@/components/settings/permissions/PermissionManager.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import type { PageProps } from '@inertiajs/core';
import UsersTabs from '@/components/settings/UsersTabs.vue';

interface PagePropsData extends PageProps {
    permissions: Record<string, Permission[]>;
    selected_permissions: string[];
    user: User;
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: '/settings/profile',
    },
    {
        title: 'Users',
        href: '/settings/users',
    },
    {
        title: 'Permissions',
        href: '/settings/permissions',
    },
];

const page = usePage<PagePropsData>().props;
const user = page.user;
const selectedPermissions = page.selected_permissions;
const permissions = page.permissions;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Permissions Settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full">
                <HeadingSmall title="Permissions" description="Permissions are used to grant users access the system, grouped by module name." />
                <UsersTabs />
                <ContentSection>
                    <PermissionManager :user :selected-permissions :permissions />
                </ContentSection>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
