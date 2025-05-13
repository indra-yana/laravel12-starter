<script setup lang="ts" generic="TData, TValue">
import { computed, onMounted, reactive, ref } from 'vue';
import { DeleteIcon, EyeIcon, WrenchIcon } from 'lucide-vue-next';
import { FlexRender, getCoreRowModel, getExpandedRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable, } from '@tanstack/vue-table';
import { router } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { User } from '@/types';
import { useUserTableStore } from './useUserTableStore';
import Button from '@/components/ui/button/Button.vue';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuCheckboxItem from '@/components/ui/dropdown-menu/DropdownMenuCheckboxItem.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuLabel from '@/components/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/DropdownMenuSeparator.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import Input from '@/components/ui/input/Input.vue';
import Pagination from './Pagination.vue';

export interface PaginationLink {
  url: string | null;
  separator: string | boolean | null | true;
  label: string;
  active: boolean;
}

export interface Pagination {
    firstPageUrl: string,
    lastPageUrl: string,
    prevPageUrl: string,
    nextPageUrl: string,
    totalPage: number,
    perPage: number,
    currentPage: number,
    lastPage: number,
    links: PaginationLink[],
}

const props = defineProps(['columns', 'data', 'filters']);
const users = ref<User[]>(props.data.data);
const pagination = reactive<Pagination>({
    firstPageUrl: props.data.first_page_url,
    lastPageUrl: props.data.last_page_url,
    prevPageUrl: props.data.prev_page_url,
    nextPageUrl: props.data.next_page_url,
    totalPage: props.data.total,
    perPage: props.data.per_page,
    currentPage: props.data.current_page,
    lastPage: props.data.last_page,
    links: props.data.links,
});

const pageIndex = ref(pagination.currentPage - 1);
const pageSize = ref(pagination.perPage || 10);

onMounted(() => {
    console.log(props.data);
})

const selectedRows = computed(() => table.getSelectedRowModel().rows.map(row => row.original));
const userTableStore = useUserTableStore();

const table = useVueTable({
    get data() { return users },
    get columns() { return props.columns },
    state: {
        get sorting() { return userTableStore.sorting },
        get columnFilters() { return userTableStore.columnFilters },
        get columnVisibility() { return userTableStore.columnVisibility },
        get rowSelection() { return userTableStore.rowSelection },
        get expanded() { return userTableStore.expanded },
        get pagination() { return userTableStore.pagination },
    },
    onSortingChange: userTableStore.setSorting,
    onColumnFiltersChange: userTableStore.setColumnFilters,
    onColumnVisibilityChange: userTableStore.setColumnVisibility,
    onRowSelectionChange: userTableStore.setRowSelection,
    onExpandedChange: userTableStore.setExpanded,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    
    getPaginationRowModel: getPaginationRowModel(),
    manualPagination: true,
    rowCount: pagination.totalPage,
    // onPaginationChange: userTableStore.setPagination,
    onPaginationChange: (updater) => {
        // const nextPage = typeof updater === 'function'
        // ? updater({ pageIndex: pageIndex.value }).pageIndex
        // : updater.pageIndex;

        userTableStore.setPagination(updater);
        pageIndex.value = userTableStore.pagination.pageIndex;
        pageSize.value = userTableStore.pagination.pageSize;
        
        router.get(route('users.index'), {
            page: pageIndex.value + 1,
            per_page: pageSize.value,
            ...props.filters,
        }, {
            preserveScroll: true,
            preserveState: false,
            replace: true,
        })
    },
})

const handlePageChanged = (newIndex: number) => {
  table.setPageIndex(newIndex);
}

function deleteSelected() {
    const idsToDelete = selectedRows.value.map((r) => r.id);
    alert(`Deleting IDs: ${idsToDelete.join(', ')}`);
}

</script>

<template>
    <div class="flex items-center space-x-2 py-4">
        <div class="flex-1">
            <Input class="max-w-sm" placeholder="Filter emails..." :model-value="table.getColumn('email')?.getFilterValue() as string" @update:model-value="table.getColumn('email')?.setFilterValue($event)" />
        </div>
        <div class="flex items-center justify-between space-x-2 px-2">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="ml-auto">
                        <WrenchIcon class="size-4" />
                        Actions
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>Bulk Actions</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <Button variant="ghost" class="ml-auto text-destructive" size="sm" @click="deleteSelected" :disabled="!selectedRows.length">
                        <DeleteIcon class="size-4 me-2" />
                        Delete Selected
                    </Button>
                </DropdownMenuContent>
            </DropdownMenu>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="ml-auto">
                        <EyeIcon class="size-4" />
                        Columns
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>Toggle Columns</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((column) => column.getCanHide())" :key="column.id" class="capitalize" :modelValue="column.getIsVisible()" @update:modelValue="(value) => {
                        column.toggleVisibility(!!value)
                    }">
                        {{ column.id }}
                    </DropdownMenuCheckboxItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </div>
    <div class="border rounded-md">
        <Table>
            <TableHeader>
                <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                    <TableHead v-for="header in headerGroup.headers" :key="header.id">
                        <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <template v-for="row in table.getRowModel().rows" :key="row.id">
                        <TableRow :data-state="row.getIsSelected() ? 'selected' : undefined">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="row.getIsExpanded()">
                            <TableCell :colspan="row.getAllCells().length">
                                {{ JSON.stringify(row.original) }}
                            </TableCell>
                        </TableRow>
                    </template>
                </template>
                <template v-else>
                    <TableRow>
                        <TableCell :colspan="columns.length" class="h-24 text-center">
                            No results.
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
    <div class="flex items-center justify-end space-x-2 py-4">
        <div class="flex-1 text-sm text-muted-foreground">
            {{ table.getFilteredSelectedRowModel().rows.length }} of
            {{ table.getFilteredRowModel().rows.length }} row(s) selected.
        </div>
        <Pagination class="space-x-2 py-4" :table="table" :pagination="pagination" @pageChanged="handlePageChanged" />
    </div>
</template>