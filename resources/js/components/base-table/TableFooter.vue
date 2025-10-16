<script setup lang="ts" generic="TData, TValue">
import { PaginationType } from '@/types';
import PageLength from './PageLength.vue';
import Pagination from './Pagination.vue';
import type { Table } from '@tanstack/table-core';

interface Props {
    table: Table<TData>,
    pagination: PaginationType<TData>
}

const props = defineProps<Props>()
const emit = defineEmits<{
    (e: 'pageChanged', page: number): void
}>()

function onPageChanged(page: number) {
    emit('pageChanged', page)
}
</script>

<template>
    <div class="flex flex-wrap items-start justify-end gap-2 sm:space-x-2">
        <div class="flex-1 text-sm text-muted-foreground py-2">
            {{ trans('label.selected_rows', {
                selected: table.getFilteredSelectedRowModel().rows.length,
                total: table.getFilteredRowModel().rows.length
            }) }}
        </div>

        <PageLength :table="table" />
        <Pagination class="space-x-2" :table="table" :pagination="pagination" @pageChanged="onPageChanged" />
    </div>
</template>
