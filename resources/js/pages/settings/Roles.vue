<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { User, type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import RoleManager, { Role, RoleDef } from '@/components/settings/roles/RoleManager.vue';
import SettingsLayout from '@/layouts/SettingLayout.vue';
import type { PageProps } from '@inertiajs/core';
import UsersTabs from '@/components/settings/UsersTabs.vue';

interface PagePropsData extends PageProps {
    roles: Role[];
    selected_role: RoleDef[];
    user: User;
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: trans('label.settings'),
        href: '/settings/profile',
    },
    {
        title: trans('label.users'),
        href: '/settings/users',
    },
    {
        title: trans('label.roles'),
        href: '/settings/roles',
    },
];

const page = usePage<PagePropsData>().props;
const user = page.user;
const selectedRoles = page.selected_role;
const roles = page.roles;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Roles Settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full">
                <HeadingSmall :title="trans('label.roles')" :description="trans('label.roles_description')" />
                <UsersTabs />
                <ContentSection>
                    <RoleManager :user :roles :selected-roles />
                </ContentSection>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
