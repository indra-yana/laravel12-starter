<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
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

const page = usePage();
const users = page.props.users;

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Users settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full">
                <HeadingSmall title="Users settings" description="Create & manage individual user accounts within the system." />
                <UsersTabs />
                <UsersTable :users="users" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
