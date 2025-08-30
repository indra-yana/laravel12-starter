<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { User, type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import RoleManager, { Role, RoleDef } from '@/components/settings/roles/RoleManager.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import type { PageProps } from '@inertiajs/core';
import UsersTabs from '@/components/settings/UsersTabs.vue';

interface PagePropsData extends PageProps {
    roles: Role[];
    selected_role: RoleDef[];
    user: User;
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Roles settings',
        href: '/settings/appearance',
    },
];

const page = usePage<PagePropsData>().props;
const user = page.user;
const selectedRoles = page.selected_role;
const roles = page.roles;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Roles settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Roles settings" description="Roles are collection set of permissions that assigned to users within their level." />
                <UsersTabs />
                <RoleManager :user :roles :selected-roles />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
