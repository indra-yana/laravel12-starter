<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

interface Props {
    year?: number | string;
    routeName?: string;
}

const props = defineProps<Props>();
const currentYear = new Date().getFullYear();
const years = computed(() => {
    const arr: number[] = [];
    for (let i = currentYear - 5; i <= currentYear + 2; i++) {
        arr.push(i);
    }

    return arr.reverse();
});

const selectedYear = ref<string>(
    String(props.year ?? currentYear)
);

function onYearSelected(year: any): any {
    router.get(route(props.routeName ?? "periods.index"), { year },
        {
            preserveScroll: true,
            preserveState: false,
        }
    );
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
