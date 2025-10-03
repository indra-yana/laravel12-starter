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
        title: trans('label.settings'),
        href: '/settings/profile',
    },
    {
        title: trans('label.appearance'),
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Appearance Settings" />

        <SettingsLayout>
            <div class="space-y-6 w-full xl:w-3xl">
                <HeadingSmall :title="trans('label.appearance')" :description="trans('label.update_your_accounts_appearance_settings')" />

                <div class="flex flex-col sm:flex-row justify-between gap-2">
                    <HeadingSmall :separator="false" :title="trans('label.theme')" :description="trans('label.select_the_theme_for_the_app')" />
                    <div>
                        <AppearanceTabs />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-2">
                    <HeadingSmall :separator="false" :title="trans('label.language')" :description="trans('label.select_the_language_support')" />
                    <div>
                        <LangSwitcher />
                    </div>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
