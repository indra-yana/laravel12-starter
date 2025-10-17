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
import { ref } from 'vue';
import MonthSelectMultiple from '@/components/logbook/MonthSelectMultiple.vue';
import { Button } from '@/components/ui/button';
import { Download } from 'lucide-vue-next';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Separator } from '@/components/ui/separator';

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

function handleYearSelected(year: number) {
    router.get(route("logbook.racapitulation.index"), { month: currentMonth, year }, {
        preserveScroll: true,
        preserveState: false,
    });
}

function handleMonthSelection(months: number[]) {
    monthsSelected.value = months;
    console.log("Bulan terpilih:", months);
}

function handleDownload() {
    if (!monthsSelected.value.length) {
        alert('Silakan pilih minimal 1 bulan terlebih dahulu.')
        return
    }

    const baseUrl = '/logbook/racapitulation/print'
    const monthQuery = monthsSelected.value
        .map((m) => `month[]=${encodeURIComponent(m)}`)
        .join('&')

    const yearQuery = `year=${encodeURIComponent(currentYear)}`
    const url = `${baseUrl}?${monthQuery}&${yearQuery}`

    window.location.href = url
}

</script>

<template>

    <Head title="Logbook - Rekapitulasi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class=" w-full px-4 py-6">
            <div class='mb-2 flex flex-wrap items-center justify-between space-y-2'>

                <Heading :title="trans('Rekapitulasi')" :description="trans('Monitoring dan rekapitulasi pelaporan LKH & LKB')" class="mb-4" />
    
                <div class="flex flex-col md:flex-row sm:items-center gap-1 sm:gap-2 w-full sm:w-auto mb-5 px-2 py-5 border rounded-xl">
                    <Label for="year-select" class="text-md font-medium text-muted-foreground">
                        Periode
                    </Label>
                    <MonthSelectMultiple :months="monthsSelected" @month-selected="handleMonthSelection" />
                    <YearSelect :year="currentYear" @year-selected="handleYearSelected" />
                    <Button variant="info" @click="handleDownload">
                        <Download class="mr-1 h-4 w-4" />
                        {{ trans('Download') }}
                    </Button>
                </div>
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
