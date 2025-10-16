<script setup lang="ts">
import { ref, computed } from "vue";
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

interface Props {
    year?: number | string;
}

const props = defineProps<Props>();
const emits = defineEmits<{
	(e: 'yearSelected', year: number): void
}>();
const currentYear = new Date().getFullYear();
const years = computed(() => {
    const arr: number[] = [];
    for (let i = currentYear - 2; i <= currentYear + 2; i++) {
        arr.push(i);
    }

    return arr.reverse();
});

const selectedYear = ref<string>(
    String(props.year ?? currentYear)
);

function onYearSelected(year: any): any {
    emits('yearSelected', year);
}
</script>

<template>
    <Select v-model="selectedYear" @update:modelValue="onYearSelected">
        <SelectTrigger class="text-left w-full sm:w-[220px] bg-primary/40 outline-amber-800">
            <SelectValue placeholder="Pilih Tahun" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="year in years" :key="year" :value="String(year)">
                    {{ year }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
