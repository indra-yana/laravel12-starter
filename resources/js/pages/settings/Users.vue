<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import UsersTable from '@/components/settings/users-table/UsersTable.vue';
import UsersTabs from '@/components/settings/UsersTabs.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Users settings',
        href: '/settings/appearance',
    },
];

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Users settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full">
                <HeadingSmall title="Users settings" description="Create & manage individual user accounts within the system." />
                <UsersTabs />
                <UsersTable />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
