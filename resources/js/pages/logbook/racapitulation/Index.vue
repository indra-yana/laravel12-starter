<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import RecapTable from '@/components/logbook/recapitulation-table/RecapTable.vue';
import ContentSection from '@/layouts/ContentSection.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Logbook',
        href: '/logbook/recapitulation',
    },
    {
        title: 'Rekapitulasi',
        href: '/logbook/recapitulation',
    },
];
</script>

<template>
    <Head title="Logbook - Rekapitulasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" w-full px-4 py-6">
            <Heading :title="trans('Rekapitulasi')" :description="trans('Monitoring dan rekapitulasi pelaporan LKH & LKB')" class="mb-4" />

            

            <div class="flex w-full overflow-y-hidden overflow-x-auto sticky h-screen px-1">
                <ContentSection>
                    <RecapTable />
                </ContentSection>
            </div>
        </div>
    </AppLayout>
</template>
