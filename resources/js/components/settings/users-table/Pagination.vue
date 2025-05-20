<script setup lang="ts" generic="TData, TValue">
import { ArrowLeftIcon, ArrowRightIcon, ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import { Pagination, PaginationLink } from './UsersTable.vue';
import { type Table } from '@tanstack/vue-table';
import { User } from '@/types';

interface Props {
    table: Table<TData>,
    pagination: Pagination<User>,
}

const props = defineProps<Props>()
const emit = defineEmits([
    'pageChanged',
])

const numericLinks = computed((): PaginationLink[] => {
    const items = props.pagination.links.filter(link => isNumberLabel(link.label));
    const pageIndex = items.find(link => link.active);
    const current = pageIndex ? parseInt(pageIndex.label) : 1;
    const total = props.pagination.lastPage;
    const pageNumbers: (PaginationLink | { label: string; separator: true })[] = [];

    const addPage = (page: number) => {
        const exists = pageNumbers.some(p => p.label === page.toString());
        if (exists) return;

        const link = items.find(l => parseInt(l.label) === page);
        if (link) pageNumbers.push(link);
    }

    // Selalu tampilkan halaman pertama
    addPage(1);

    // Ellipsis sebelum current jika gap dari halaman 2
    if (current > 3) {
        pageNumbers.push({
            label: '...',
            separator: true
        });
    }

    // Halaman sekitar current
    for (let i = current - 1; i <= current + 1; i++) {
        if (i > 1 && i < total) {
            addPage(i);
        }
    }

    // Ellipsis setelah current jika gap ke last page
    if (current < total - 1) {
        pageNumbers.push({
            label: '...',
            separator: true
        });
    }

    // Selalu tampilkan halaman terakhir (jika lebih dari 1)
    if (total > 1) {
        addPage(total);
    }

    return pageNumbers as PaginationLink[];
})

function goToPage(link: PaginationLink | number) {
    if (typeof link === 'object' && link !== null) {
        if (!link.url || link.separator || Number(link.label) === props.pagination.pageIndex) return;
        emit('pageChanged', Number(link.label) - 1);
    } else {
        emit('pageChanged', Number(link) - 1);
    }
}

const isNumberLabel = (label: string) => /^\d+$/.test(label);

</script>

<template>
    <div class="flex items-center justify-between px-2">
        <div class="flex items-start space-x-4 lg:space-x-6">
            <div class="flex flex-col gap-3 items-end">
                <div class="flex items-center gap-2">
                    <Button variant="outline" class="hidden w-8 h-8 p-0 lg:flex" :disabled="!table.getCanPreviousPage()" @click="table.setPageIndex(0)">
                        <span class="sr-only">Go to first page</span>
                        <ArrowLeftIcon class="w-4 h-4" />
                    </Button>
                    <Button variant="outline" class="w-8 h-8 p-0" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
                        <span class="sr-only">Go to previous page</span>
                        <ChevronLeftIcon class="w-4 h-4" />
                    </Button>
                    <Button v-for="link in numericLinks" :variant="link.active ? 'default' : 'outline'" :key="link.label" :disabled="!link.url" @click="goToPage(link)" :class="[
                        'w-8 h-8 p-0 hidden md:flex',
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
                <div class="flex  items-end justify-end text-sm font-medium">
                    Page {{ table.getState().pagination.pageIndex + 1 }} of {{ table.getPageCount() }}
                </div>
            </div>
        </div>
    </div>
</template>