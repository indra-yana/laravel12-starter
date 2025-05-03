<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import UsersTabs from '@/components/settings/UsersTabs.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Permissions settings',
        href: '/settings/appearance',
    },
];

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Permissions settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Permissions settings" description="Permissions are used to grant users to access system resources." />
                <UsersTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
