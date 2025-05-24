<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import PermissionManager from '@/components/settings/permissions/PermissionManager.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import type { PageProps } from '@inertiajs/core';
import UsersTabs from '@/components/settings/UsersTabs.vue';

interface PagePropsData extends PageProps {
    permissions: Record<string, any[]>;
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Permissions settings',
        href: '/settings/appearance',
    },
];

const page = usePage<PagePropsData>().props;
const permissions = page.permissions;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Permissions settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Permissions settings" description="Permissions are used to grant users to access system resources." />
                <UsersTabs />
                <PermissionManager :permissions="permissions" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
