<script setup lang="ts">
import { monthNames } from "@/lib/utils";
import { ref } from "vue";
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

interface Props {
    month?: number | string;
}

const props = defineProps<Props>();
const emits = defineEmits<{
    (e: 'monthSelected', month: number): void
}>();
const currentMonth = new Date().getMonth() + 1;
const selectedMonth = ref<string>(
    String(props.month ?? currentMonth)
);

function onMonthSelected(month: any): any {
    emits('monthSelected', Number(month));
}
</script>

<template>
    <Select v-model="selectedMonth" @update:modelValue="onMonthSelected">
        <SelectTrigger class="text-left w-full sm:w-[190px] bg-primary/40 outline-amber-800">
            <SelectValue placeholder="Pilih Bulan" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="(monthLabel, index) in monthNames" :key="index + 1" :value="String(index + 1)">
                    {{ monthNames[index] }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
