<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import Heading from '@/components/Heading.vue';
import MonthSelect from '@/components/logbook/MonthSelect.vue';
import RecapTable from '@/components/logbook/recapitulation-table/RecapTable.vue';
import type { PageProps } from '@inertiajs/core';
import YearSelect from '@/components/logbook/YearSelect.vue';

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

interface PagePropsData extends PageProps {
    selected_month: string;
    selected_year: string;
}

const props = usePage<PagePropsData>().props;
const currentMonth = props.selected_month || new Date().getMonth() + 1;
const currentYear = props.selected_year || new Date().getFullYear();

function handleMonthSelected(month: number) {
    router.get(route("logbook.racapitulation.index"), { month, year: currentYear }, {
		preserveScroll: true,
		preserveState: false,
	});
}

function handleYearSelected(year: number) {
    router.get(route("logbook.racapitulation.index"), { month: currentMonth, year }, {
		preserveScroll: true,
		preserveState: false,
	});
}
</script>

<template>

    <Head title="Logbook - Rekapitulasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" w-full px-4 py-6">
            <Heading :title="trans('Rekapitulasi')" :description="trans('Monitoring dan rekapitulasi pelaporan LKH & LKB')" class="mb-4" />

            <div class="flex flex-col md:flex-row sm:items-center gap-1 sm:gap-2 w-full sm:w-auto mb-5 px-2 pt-3">
                <Label for="year-select" class="text-md font-medium text-muted-foreground">
                    Periode
                </Label>
                <MonthSelect :month="currentMonth" @month-selected="handleMonthSelected" />
                <YearSelect :year="currentYear" @year-selected="handleYearSelected" />
            </div>
            <div class="flex w-full overflow-y-hidden overflow-x-auto sticky h-screen px-1">
                <ContentSection>
                    <RecapTable />
                </ContentSection>
            </div>
        </div>
    </AppLayout>
</template>
