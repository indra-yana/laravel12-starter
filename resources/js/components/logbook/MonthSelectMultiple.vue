<script setup lang="ts">
import { Checkbox } from "@/components/ui/checkbox";
import { monthNames } from "@/lib/utils";
import { ref, onMounted } from "vue";
import { Select, SelectTrigger, SelectValue, SelectContent, } from "@/components/ui/select";

interface Props {
	months?: number[];
}

const props = defineProps<Props>();
const emits = defineEmits<{
	(e: "monthSelected", months: number[]): void;
}>();
const currentMonth = new Date().getMonth() + 1;
const selectedMonths = ref<number[]>(props.months?.length ? props.months : [currentMonth]);
const selectedLabel = ref<string>("");
const listMonths = ref<Record<string, boolean>>({
	Januari: false,
	Februari: false,
	Maret: false,
	April: false,
	Mei: false,
	Juni: false,
	Juli: false,
	Agustus: false,
	September: false,
	Oktober: true,
	November: false,
	Desember: false
});

function updateSelectedLabel() {
	if (selectedMonths.value.length === 0) {
		selectedLabel.value = "Pilih Bulan";
	} else if (selectedMonths.value.length === 1) {
		selectedLabel.value = monthNames[selectedMonths.value[0] - 1];
	} else {
		selectedLabel.value = `${selectedMonths.value.length} bulan dipilih`;
	}
}

function toggleMonth(month: number) {
	const idx = selectedMonths.value.indexOf(month);
	if (idx !== -1) {
		selectedMonths.value.splice(idx, 1);
	} else {
		selectedMonths.value.push(month);
	}

	updateSelectedLabel();
	emits("monthSelected", selectedMonths.value);
}

onMounted(() => {
	updateSelectedLabel();
});

const isDisabled = (monthNumber: number) => {
  // Jika sudah pilih 4 bulan dan bulan ini belum dipilih, disable
  return selectedMonths.value.length >= 4 && !selectedMonths.value.includes(monthNumber)
}
</script>

<template>
	<Select>
		<SelectTrigger class="text-left w-full sm:w-[220px] bg-primary/40 outline-amber-800">
			<SelectValue :placeholder="selectedLabel" />
		</SelectTrigger>
		<SelectContent>
			<div class="flex flex-col gap-2 p-2">
				<label v-for="(monthLabel, index) in monthNames" :key="index" class="flex items-center gap-2 cursor-pointer">
					<Checkbox v-model="listMonths[monthLabel]" @update:model-value="() => toggleMonth(index + 1)" :disabled="isDisabled(index + 1)"/>
					<span :class="{'text-muted-foreground': isDisabled(index + 1)}">{{ monthLabel }}</span>
				</label>
			</div>
		</SelectContent>
	</Select>
</template>
