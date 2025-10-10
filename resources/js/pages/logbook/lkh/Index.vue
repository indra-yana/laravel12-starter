<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import FullCalendarView from '@/components/logbook/FullCalendarView.vue';
import Heading from '@/components/Heading.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Logbook',
        href: '/logbook',
    },
    {
        title: 'LKH',
        href: '/logbook/lkh',
    },
];
</script>

<template>
    <Head title="Logbook - LKH" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" w-full px-4 py-6">
            <Heading :title="trans('LKH')" :description="trans('Buat dan kelola Laporan Kerja Harian')" class="mb-4" />
            <FullCalendarView />
        </div>
    </AppLayout>
</template>
