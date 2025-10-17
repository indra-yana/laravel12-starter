<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Download } from 'lucide-vue-next';
import { Head, usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import Heading from '@/components/Heading.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import MonthSelectMultiple from '@/components/logbook/MonthSelectMultiple.vue';
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
const monthsSelected = ref<number[]>([currentMonth as number]);
const yearSelected = ref<number>(currentYear as number);

function handleYearSelected(year: number) {
    yearSelected.value = year;
}

function handleMonthSelection(months: number[]) {
    monthsSelected.value = months;
}

function handleDownload() {
    if (!monthsSelected.value.length) {
        alert('Silakan pilih minimal 1 bulan terlebih dahulu.');
        return;
    }

    const baseUrl = '/logbook/racapitulation/print';
    const monthQuery = monthsSelected.value
        .map((m) => `month[]=${encodeURIComponent(m)}`)
        .join('&');

    const yearQuery = `year=${encodeURIComponent(yearSelected.value)}`;
    const url = `${baseUrl}?${monthQuery}&${yearQuery}`;

    window.location.href = url;
}

</script>

<template>

    <Head title="Logbook - Rekapitulasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" w-full px-4 py-6">
            <div class='mb-2 flex flex-wrap items-center justify-between space-y-2'>

                <Heading :title="trans('Rekapitulasi')" :description="trans('Monitoring dan rekapitulasi pelaporan LKH & LKB')" class="mb-4" />

                <fieldset class="border rounded-lg p-2.5 w-full sm:w-auto">
                    <legend class="px-2 text-sm font-medium ">
                        <Label for="year-select" class="text-md font-medium">
                            Download Rekapitulasi
                        </Label>
                    </legend>
                    <span class="text-sm text-muted-foreground">{{ trans('Pilih bulan dan tahun di bawah ini lalu klik Download') }}</span>
                    <div class="flex flex-col md:flex-row sm:items-center gap-1 sm:gap-2 w-full sm:w-auto mt-2">
                        <MonthSelectMultiple :months="monthsSelected" @month-selected="handleMonthSelection" />
                        <YearSelect :year="currentYear" @year-selected="handleYearSelected" />
                        <Button variant="info" @click="handleDownload">
                            <Download class="mr-1 h-4 w-4" />
                            {{ trans('Download') }}
                        </Button>
                    </div>
                </fieldset>
            </div>

            <div class=" overflow-y-hidden overflow-x-auto sticky h-screen px-1">
                <HeadingSmall class="w-full" :title="trans('Daftar Rekapitulasi')" :description="trans('Menampilkan progres pelaporan bulan berjalan')" />
                <ContentSection>
                    <RecapTable />
                </ContentSection>
            </div>
        </div>
    </AppLayout>
</template>
