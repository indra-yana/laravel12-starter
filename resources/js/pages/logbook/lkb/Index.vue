<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { computed, ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Info, Plus } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';
import { ScrollArea } from '@/components/ui/scroll-area';
import { type BreadcrumbItem } from '@/types';
import AddTargetsModal, { MonthlyWorkProps } from '@/components/logbook/AddTargetsModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import Heading from '@/components/Heading.vue';
import LkbCard, { MonthlyPeriodProps } from '@/components/logbook/LkbCard.vue';
import type { PageProps } from '@inertiajs/core';
import YearSelect from '@/components/logbook/YearSelect.vue';

interface PagePropsData extends PageProps {
	monthly_periods: MonthlyPeriodProps[];
	selected_year: string;
}

const breadcrumbs: BreadcrumbItem[] = [
	{
		title: 'Logbook',
		href: '/logbook/lkb',
	},
	{
		title: 'LKB',
		href: '/logbook/lkb',
	},
];

const props = usePage<PagePropsData>().props;
const currentYear = props.selected_year || new Date().getFullYear()
const monthlyPeriods = props.monthly_periods;
const showDeleteConfirm = ref(false);
const deleteform = useForm<Omit<MonthlyPeriodProps, 'id'>>({
	id: null,
});
const addMonthlyPeriodForm = useForm<Omit<MonthlyPeriodProps, 'id'>>({
	id: null,
	year: currentYear,
});

const showTargetModal = ref(false);
const selectedPeriodId = ref<number | null>(null);
const selectedMonthlyPeriod = ref<MonthlyPeriodProps | null>(null);
const selectedExistingTargets = ref<MonthlyWorkProps[] | null>();
const isLastMonthDecember = computed(() => {
	if (!monthlyPeriods.length) return false;
	const last = monthlyPeriods[0];
	return last.month === 12
})

function handleMonthlyPeriodAdd() {
	addMonthlyPeriodForm.post(route('logbook.lkb.store'), {
		preserveScroll: true,
		preserveState: ({ props }) => !!Object.keys(props.errors).length,
		onError: () => { },
		onSuccess: () => {
		},
	});
}

function handleMonthlyPeriodDelete(id: number) {
	deleteform.id = id || null;
	showDeleteConfirm.value = true;
}

function deleteMonthlyPeriodConfirmed() {
	const url = route('logbook.lkb.delete');
	deleteform.delete(url, {
		preserveScroll: true,
		preserveState: ({ props }) => !!Object.keys(props.errors).length,
		onError: () => handleMonthlyPeriodDeleteCancel(),
		onSuccess: () => {
			handleMonthlyPeriodDeleteCancel();
		},
	});
}

function handleMonthlyPeriodDeleteCancel() {
	showDeleteConfirm.value = false;
}

function handleAddTarget(id: number) {
	selectedPeriodId.value = id;
	showTargetModal.value = true;
	selectedMonthlyPeriod.value = monthlyPeriods.filter((item) => item.id == id)[0];
	selectedExistingTargets.value = selectedMonthlyPeriod.value.monthlyworks;
}

function handleTargetSaved() {
	showTargetModal.value = false
}

</script>

<template>

	<Head title="Logbook - LKH" />

	<AppLayout :breadcrumbs="breadcrumbs">

		<ConfirmDialog id="delete-confirm" type="delete" :open="showDeleteConfirm" :onConfirm="deleteMonthlyPeriodConfirmed" :onCancel="handleMonthlyPeriodDeleteCancel" :loading="deleteform.processing" :title="trans('label.delete_confirm')" :description="trans('label.delete_data_description')"></ConfirmDialog>

		<AddTargetsModal :show="showTargetModal" :monthlyPeriodId="selectedPeriodId" :monthlyPeriod="selectedMonthlyPeriod" :existingTargets="selectedExistingTargets" @close="showTargetModal = false" @saved="handleTargetSaved" />

		<div class="px-4 py-6">
			<Heading :title="trans('LKB')" :description="trans('Buat dan kelola Laporan Kerja Bulanan')" class="mb-4" />
			<div class="flex flex-col lg:flex-row gap-4">
				<div class="w-full md:w-3/5 lg:w-1/3 xl:w-1/4 2xl:w-1/5">
					<div className="flex items-center justify-between py-2">
						<div className="flex gap-2">
							<h2 className="text-xl font-bold">Petunjuk</h2>
						</div>
						<Button variant="outline" class="rounded-md size-8">
							<Info class="size-4" />
						</Button>
					</div>
					<ScrollArea type="auto" orientation="vertical" class="bg-background w-full min-w-40 px-1 py-2 md:block">
						<div class="mb-3">
							<ul class="list-disc space-y-2 text-muted-foreground">
								<li>
									<span class="font-semibold">Membuat LKB baru:</span>
									<ol class="list-decimal text-sm">
										<li>{{ trans('Klik tombol "Tambah LKB" untuk membuat periode LKB baru.') }}</li>
										<li>{{ trans('Isi formulir sasaran yang diperlukan. ') }}</li>
										<li>{{ trans('Klik simpan.') }}</li>
									</ol>
								</li>
								<li>
									<span class="font-bold">Menambah Sasaran:</span>
									<ol class="list-decimal text-sm">
										<li>{{ trans('Klik pada tombol "+ Tambah Sasaran".') }}</li>
										<li>{{ trans('Isi formulir sasaran yang diperlukan.') }}</li>
										<li>{{ trans('Klik simpan perubahan.') }}</li>
									</ol>
								</li>
								<li>
									<span class="font-bold text-destructive">Menghapus LKB:</span>
									<ol class="list-decimal text-sm">
										<li>{{ trans('Klik tombol hapus (icon trash) pada LKB.') }}</li>
										<li>{{ trans('Klik "Ya" untuk mengonfirmasi.') }}</li>
									</ol>
								</li>
							</ul>
						</div>

					</ScrollArea>
				</div>
				<div class="flex-1 overflow-y-hidden sticky h-screen p-2 border bg-secondary shadow-lg rounded-lg">
					<div class="flex flex-col md:flex-row sm:items-center gap-1 sm:gap-2 w-full sm:w-auto mb-5 px-2 pt-3">
						<Label for="year-select" class="text-md font-medium text-muted-foreground">
							Periode
						</Label>
						<YearSelect :year="currentYear" routeName="logbook.lkb.index" />
					</div>
					<ContentSection>
						<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
							<Card v-if="!isLastMonthDecember" class="flex items-center justify-center border-dashed border-2 border-emerald-300 rounded-2xl cursor-pointer transition-all duration-300 hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20" :class="{ 'cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20': !addMonthlyPeriodForm.processing, 'cursor-not-allowed opacity-50': addMonthlyPeriodForm.processing }" @click="!addMonthlyPeriodForm.processing && handleMonthlyPeriodAdd()">
								<CardContent class="flex flex-col items-center justify-center py-8">
									<Plus class="size-8 text-emerald-500 mb-2" />
									<span class="font-medium text-sm" :class="addMonthlyPeriodForm.processing ? 'text-gray-400' : 'text-emerald-600'">
										{{ addMonthlyPeriodForm.processing ? trans('Menambahkan...') : trans('Tambah LKB') }}
									</span>
								</CardContent>
							</Card>
							<template v-if="monthlyPeriods.length">
								<LkbCard v-for="(period, index) in monthlyPeriods" :key="index" v-bind="period" :index="index" @addTarget="handleAddTarget" @delete="handleMonthlyPeriodDelete" />
							</template>
						</div>
					</ContentSection>
				</div>
			</div>
		</div>
	</AppLayout>
</template>
