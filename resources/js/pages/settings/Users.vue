<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Separator } from '@/components/ui/separator';
import { type BreadcrumbItem } from '@/types';
import { UserPlus2 } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/SettingLayout.vue';
import UserForm from '@/components/settings/user-form/UserForm.vue';
import UsersTable from '@/components/settings/users-table/UsersTable.vue';
import UsersTabs from '@/components/settings/UsersTabs.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: '/settings/profile',
    },
    {
        title: 'Users',
        href: '/settings/users',
    },
];

const showUserForm = ref(false);

function handleAddUser() {
    showUserForm.value = true;
}

function handleOpenChange(close: boolean) {
    showUserForm.value = close;
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Users Settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full">
                <div className='mb-2 flex flex-wrap items-center justify-between space-y-2'>
                    <HeadingSmall title="Users" description="Create & manage user accounts within the system." :separator="false" />
                    <Button variant='outline-default' @click="handleAddUser">
                        <UserPlus2 /> Add User
                    </Button>
                    <UserForm :open="showUserForm" :onOpenChange="handleOpenChange" title="Add New User" description="Create new user here. Click save when you're done." />
                </div>

                <Separator class="mt-4 hidden lg:block" />

                <UsersTabs />
                <ContentSection>
                    <UsersTable />
                </ContentSection>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
