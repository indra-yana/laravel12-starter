<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { Separator } from '@/components/ui/separator';
import { type BreadcrumbItem } from '@/types';
import { UserPlus2 } from 'lucide-vue-next';
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

function handleAdd() {
    console.log('Add new user');
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Users settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full">
                <div className='mb-2 flex flex-wrap items-center justify-between space-y-2'>
                    <HeadingSmall title="Users settings" description="Create & manage individual user accounts within the system." :separator="false" />
                    <Button variant='outline-default' @click="handleAdd">
                        <UserPlus2 /> Add User
                    </Button>
                </div>
                <Separator class="mt-4 hidden lg:block" />
                <UsersTabs />
                <UsersTable />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
