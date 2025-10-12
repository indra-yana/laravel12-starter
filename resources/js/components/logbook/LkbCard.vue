<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from "@/components/ui/card";
import { Target, Trash2, EllipsisVertical, Plus, Printer } from "lucide-vue-next";
import { Button } from "../ui/button";

export interface MonthlyPeriodProps {
	id?: string | number | null;
	month: number;
	monthly_work_count: number;
	year: number;
	[key: string]: any;
}

const emit = defineEmits<{
  (e: 'delete', id: number): void
  (e: 'addTarget', id: number): void
}>()

const props = defineProps<Partial<MonthlyPeriodProps> & {
	index?: number;
}>()

const monthNames = [
	"Januari", "Februari", "Maret", "April", "Mei", "Juni",
	"Juli", "Agustus", "September", "Oktober", "November", "Desember"
]
</script>

<template>
	<Card class="bg-card rounded-2xl group shadow-sm overflow-hidden h-full grid transition-all duration-300 hover:shadow-lg relative gap-0 py-2.5 px-0">
		<CardHeader class="pb-0 px-3.5">
			<CardTitle>
				<button @click="emit('addTarget', id as number)" class="block group relative z-10">
					<div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24" />
					<h3 class="text-xl font-bold text-left text-gray-900 dark:text-white leading-tight mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
						{{ monthNames[(month || 1) - 1] }} <span class=" text-muted-foreground">{{ year }}</span>
					</h3>
				</button>
			</CardTitle>
		</CardHeader>

		<CardContent class="mb-4 px-3.5">
			<div class="space-y-1 mt-0">
				<div class="flex items-center">
					<Target class="size-5 text-emerald-500 mr-2 mt-0.5" />
					<p class="text-muted-foreground text-md line-clamp-1">
						Sasaran: {{ monthly_work_count || 0 }}
					</p>
				</div>
				<div class="flex items-center">
					<Printer class="size-5 text-emerald-500 mr-2 mt-0.5" />
					<Button variant="link" class="p-0 m-0 text-muted-foreground text-sm line-clamp-1">
						Cetak LKB
					</Button>
				</div>
			</div>
		</CardContent>

		<CardFooter class="relative mt-auto mb-auto flex justify-between z-10 px-3.5 py-1.5 overflow-auto">
			<Button @click="emit('addTarget', id as number)" title="Tambah Sasaran" class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium text-sm rounded-lg px-2 py-2 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 gap-1.5">
				<Plus class="size-4" />
				<span class="block sm:hidden xl:block">Tambah Sasaran</span>
			</Button>
			<div class="flex gap-2">
				<Button type="button" title="Hapus" @click="emit('delete', id as number)" class="inline-flex items-center size-9 bg-red text-red-600 rounded-full shadow-sm text-sm font-medium border border-red-100/60 hover:bg-red-50/25 transition-all duration-300 hover:shadow-md">
					<Trash2 class="size-4" />
				</Button>
				<Button type="button" title="Aksi Lainnya" class="inline-flex items-center size-9 bg-transparant text-emerald-600 rounded-full shadow-sm text-sm font-medium border border-emerald-100/60 hover:bg-emerald-50/25 transition-all duration-300 hover:shadow-md">
					<EllipsisVertical class="w-4" />
				</Button>
			</div>
		</CardFooter>
	</Card>
</template>
