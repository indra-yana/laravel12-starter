<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Logbook',
        href: '/logbook',
    },
    {
        title: 'LKB',
        href: '/logbook/lkb',
    },
];
</script>

<template>
    <Head title="Logbook - LKH" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <Heading :title="trans('LKB')" :description="trans('Buat dan kelola Laporan Kerja Bulanan')" class="mb-4" />
            <div class="flex flex-col lg:flex-row">
                <div class="w-full md:w-3/5 lg:w-1/3 xl:w-1/4 2xl:w-1/5">
                    <h1>Section 1</h1>
                </div>
                <div class="flex-1 overflow-y-hidden sticky h-screen p-2 border bg-secondary shadow-lg rounded-lg">
                    <h1>Section 2 wkwk</h1>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
