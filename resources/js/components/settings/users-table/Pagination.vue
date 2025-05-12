<script setup lang="ts" generic="TData, TValue">
import { ArrowLeftIcon, ArrowRightIcon, ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Pagination, PaginationLink } from './DataTable.vue';
import { router } from '@inertiajs/vue3';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue, } from '@/components/ui/select';
import { type Table } from '@tanstack/vue-table';
import { computed } from 'vue';

interface DataTablePaginationProps {
    table: Table<TData>,
    pagination: Pagination,
}

const props = defineProps<DataTablePaginationProps>()
const emit = defineEmits(['pageChanged'])

// const range = (start: number, end: number) =>
//     Array.from({ length: end - start + 1 }, (_, i) => start + i)

// const visiblePages = computed(() => {
//     const total = props.pagination.lastPage
//     const current = props.pagination.currentPage

//     if (total <= 7) {
//         return range(1, total)
//     }

//     const pages = new Set<number | string>()

//     pages.add(1)
//     pages.add(total)

//     if (current <= 4) {
//         range(2, 5).forEach(p => pages.add(p))
//         pages.add('...')
//     } else if (current >= total - 3) {
//         pages.add('...')
//         range(total - 4, total - 1).forEach(p => pages.add(p))
//     } else {
//         pages.add('...')
//         range(current - 1, current + 1).forEach(p => pages.add(p))
//         pages.add('...')
//     }

//     return Array.from(pages)
// })

// const numericLinks = computed(() => {
//     const items = props.pagination.links.filter(link => isNumberLabel(link.label))
//     const pages: (typeof items[0] | { label: string; separator: true })[] = []

//     let prev = 0
//     for (const link of items) {
//         const pageNum = parseInt(link.label)

//         if (prev && pageNum - prev > 1) {
//             pages.push({ label: '...', separator: true })
//         }

//         pages.push(link)
//         prev = pageNum
//     }

//     return pages;
// })

function goToPage(link: PaginationLink) {
    if (!link.url || link.separator || Number(link.label) === props.pagination.currentPage) return;

    router.visit(link.url, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess: () => {
            const url = new URL(link?.url || '');
            const page = url.searchParams.get('page') || 1;
            emit('pageChanged', Number(page) - 1);
        }
    })
}

const isNumberLabel = (label: string) => /^\d+$/.test(label);

</script>

<template>
    <div class="flex items-center justify-between px-2">
        <div class="flex items-center space-x-6 lg:space-x-8">
            <div class="flex items-center space-x-2">
                <p class="text-sm font-medium">Display</p>
                <Select :model-value="`${table.getState().pagination.pageSize}`" @update:model-value="table.setPageSize as any">
                    <SelectTrigger class="h-8 w-[70px]">
                        <SelectValue :placeholder="`${table.getState().pagination.pageSize}`" />
                    </SelectTrigger>
                    <SelectContent side="top">
                        <SelectItem v-for="pageSize in [10, 20, 30, 40, 50]" :key="pageSize" :value="`${pageSize}`">
                            {{ pageSize }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p class="text-sm font-medium">row(s)</p>
            </div>
            <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                Page {{ table.getState().pagination.pageIndex + 1 }} of {{ table.getPageCount() }}
            </div>
            <div class="flex items-center space-x-2">
                <Button variant="outline" class="hidden w-8 h-8 p-0 lg:flex" :disabled="!table.getCanPreviousPage()" @click="table.setPageIndex(0)">
                    <span class="sr-only">Go to first page</span>
                    <ArrowLeftIcon class="w-4 h-4" />
                </Button>
                <Button variant="outline" class="w-8 h-8 p-0" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
                    <span class="sr-only">Go to previous page</span>
                    <ChevronLeftIcon class="w-4 h-4" />
                </Button>
                <Button v-for="link in props.pagination.links.filter(l => isNumberLabel(l.label))" :variant="link.active ? 'secondary' : 'outline'" :key="link.label" :disabled="!link.url" @click="goToPage(link)" :class="[
                    'w-8 h-8 p-0 ',
                    !link.url ? 'cursor-not-allowed' : ''
                ]">
                    <span>{{ link.label }}</span>
                </Button>
                <Button variant="outline" class="w-8 h-8 p-0" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
                    <span class="sr-only">Go to next page</span>
                    <ChevronRightIcon class="w-4 h-4" />
                </Button>
                <Button variant="outline" class="hidden w-8 h-8 p-0 lg:flex" :disabled="!table.getCanNextPage()" @click="table.setPageIndex(table.getPageCount() - 1)">
                    <span class="sr-only">Go to last page</span>
                    <ArrowRightIcon class="w-4 h-4" />
                </Button>
            </div>
        </div>
    </div>
</template>