<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Head, useForm } from '@inertiajs/vue3';
import { Info, Plus } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';
import { ScrollArea } from '@/components/ui/scroll-area';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import ContentSection from '@/layouts/ContentSection.vue';
import Heading from '@/components/Heading.vue';
import LkbCard, { MonthlyPeriodProps } from '@/components/logbook/LkbCard.vue';
import YearSelect from '@/components/logbook/YearSelect.vue';

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

const currentMonth = new Date().getMonth() + 1
const currentYear = new Date().getFullYear()
const monthlyPeriods = ref<MonthlyPeriodProps[]>(
	Array.from({ length: currentMonth }, (_, i) => ({
		id: i + 1,
		month: i + 1,
		year: currentYear,
		monthly_work_count: Math.floor(Math.random() * 5),
	}))
)
const showDeleteConfirm = ref(false);
const deleteform = useForm<Omit<MonthlyPeriodProps, 'id'>>({
    id: null,
});

function handleAddTarget(id: number) {
	// TODO: Create target next target 
	// After success show modal to create multiple sasaran
	console.log("Tambah target untuk ID:", id)
}

// function handleDelete(id: number) {
// 	const index = monthlyPeriods.value.findIndex((item) => item.id === id)
// 	if (index !== -1) {
// 		monthlyPeriods.value.splice(index, 1)
// 	}
// }

function handleAddPeriod() {
	const nextId = monthlyPeriods.value.length + 1
	monthlyPeriods.value.push({
		id: nextId,
		month: nextId,
		year: currentYear,
		monthly_work_count: 0,
	})
}

function handleDelete(id: number) {
	console.log("Handle delete untuk ID:", id)

    deleteform.id = id || null;
    showDeleteConfirm.value = true;
}

function deleteConfirmed() {
    const url = route('logbook.lkh.delete');
    deleteform.delete(url, {
        preserveScroll: true,
        preserveState: ({ props }) => !!Object.keys(props.errors).length,
        onError: () => handleDeleteCancel(),
        onSuccess: () => {
            handleDeleteCancel();
        },
    });
}

function handleDeleteCancel() {
    showDeleteConfirm.value = false;
}

</script>

<template>

	<Head title="Logbook - LKH" />

	<AppLayout :breadcrumbs="breadcrumbs">

	    <ConfirmDialog id="delete-confirm" type="delete" :open="showDeleteConfirm" :onConfirm="deleteConfirmed" :onCancel="handleDeleteCancel" :loading="deleteform.processing" :title="trans('label.delete_confirm')" :description="trans('label.delete_data_description')"></ConfirmDialog>

		<div class="px-4 py-6">
			<Heading :title="trans('LKB')" :description="trans('Buat dan kelola Laporan Kerja Bulanan')" class="mb-4" />
			<div class="flex flex-col lg:flex-row">
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
										<li>{{ trans('Klik pada tanggal.') }}</li>
										<li>{{ trans('Isi formulir kegiatan. ') }}</li>
										<li>{{ trans('Klik simpan.') }}</li>
									</ol>
								</li>
								<li>
									<span class="font-bold">Mengubah LKB:</span>
									<ol class="list-decimal text-sm">
										<li>{{ trans('Klik pada kegiatan.') }}</li>
										<li>{{ trans('Ubah data pada formulir kegiatan.') }}</li>
										<li>{{ trans('Klik simpan perubahan.') }}</li>
									</ol>
								</li>
								<li>
									<span class="font-bold text-destructive">Menghapus LKB:</span>
									<ol class="list-decimal text-sm">
										<li>{{ trans('Klik pada kegiatan.') }}</li>
										<li>{{ trans('Klik tombol hapus (icon trash).') }}</li>
									</ol>
								</li>
							</ul>
						</div>

					</ScrollArea>
				</div>
				<div class="flex-1 overflow-y-hidden sticky h-screen p-2 border bg-secondary shadow-lg rounded-lg">
					<div class="flex flex-col md:flex-row sm:items-center gap-1 sm:gap-2 w-full sm:w-auto mb-5 px-2 pt-3">
						<Label for="year-select" class="text-md font-medium text-muted-foreground">
							Pilih Periode
						</Label>
						<YearSelect :year="2025" />
					</div>
					<ContentSection>
						<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
							<template v-if="monthlyPeriods.length">
								<LkbCard v-for="(period, index) in monthlyPeriods" :key="index" v-bind="period" :index="index" @addTarget="handleAddTarget" @delete="handleDelete" />
							</template>
							<Card class="flex items-center justify-center border-dashed border-2 border-emerald-300 rounded-2xl cursor-pointer transition-all duration-300 hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20" @click="handleAddPeriod">
								<CardContent class="flex flex-col items-center justify-center py-8">
									<Plus class="size-8 text-emerald-500 mb-2" />
									<span class="text-emerald-600 font-medium text-sm">Tambah LKB</span>
								</CardContent>
							</Card>
						</div>
					</ContentSection>
				</div>
			</div>
		</div>
	</AppLayout>
</template>
