<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsLayout from '@/layouts/SettingLayout.vue';
import LangSwitcher from '@/components/LangSwitcher.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: '/settings/profile',
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Appearance Settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full xl:w-3xl">
                <HeadingSmall title="Appearance" description="Update your account's appearance settings" />

                <div class="flex flex-col sm:flex-row justify-between gap-2">
                    <HeadingSmall :separator="false" title="Theme" description="Select the theme for the dashboard." />
                    <div>
                        <AppearanceTabs />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-2">
                    <HeadingSmall :separator="false" title="Language" description="Select the language for the app." />
                    <div>
                        <LangSwitcher />
                    </div>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
