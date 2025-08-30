<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { User, type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
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
        title: 'Permissions settings',
        href: '/settings/appearance',
    },
];

const page = usePage<PagePropsData>().props;
const user = page.user;
const selectedPermissions = page.selected_permissions;
const permissions = page.permissions;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Permissions settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Permissions settings" description="Permissions are used to grant users access the system, grouped by module name." />
                <UsersTabs />
                <PermissionManager :user :selected-permissions :permissions />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
