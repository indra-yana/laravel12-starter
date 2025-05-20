<script setup lang="ts" generic="TData, TValue">
import { Table } from '@tanstack/vue-table';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue, } from '@/components/ui/select';

interface Props {
    table: Table<TData>,
}

defineProps<Props>()

</script>

<template>
    <div class="flex items-center space-x-0 md:space-x-2">
        <p class="text-sm font-medium hidden md:flex">Show</p>
        <Select :model-value="`${table.getState().pagination.pageSize}`" @update:model-value="(table.setPageSize as any)">
            <SelectTrigger class="h-8 w-[70px]">
                <SelectValue :placeholder="`${table.getState().pagination.pageSize}`" />
            </SelectTrigger>
            <SelectContent side="top">
                <SelectItem v-for="pageSize in [10, 20, 30, 40, 50, 75, 100]" :key="pageSize" :value="`${pageSize}`">
                    {{ pageSize }}
                </SelectItem>
            </SelectContent>
        </Select>
        <p class="text-sm font-medium hidden md:flex">row(s)</p>
    </div>
</template>