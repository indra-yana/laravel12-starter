<script setup lang="ts" generic="TData, TValue">
import { columns } from './columns';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { DeleteIcon, EyeIcon, WrenchIcon } from 'lucide-vue-next';
import { FlexRender, getCoreRowModel, getExpandedRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable, } from '@tanstack/vue-table';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { useDebounceFn } from '@vueuse/core';
import { User } from '@/types';
import { useUserTableStore } from './useUserTableStore';
import axios from 'axios';
import Button from '@/components/ui/button/Button.vue';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuCheckboxItem from '@/components/ui/dropdown-menu/DropdownMenuCheckboxItem.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuLabel from '@/components/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/DropdownMenuSeparator.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import Input from '@/components/ui/input/Input.vue';
import Pagination from './Pagination.vue';
import type { PageProps } from '@inertiajs/core';

export interface PaginationLink {
	url: string | null;
	separator: string | boolean | null | true;
	label: string;
	active: boolean;
}

export interface UsersResponse<T> {
	data: T[]
	first_page_url: string;
	last_page_url: string;
	prev_page_url: string;
	next_page_url: string;
	total: number;
	per_page: number;
	current_page: number;
	last_page: number;
	links: PaginationLink[];
}

export interface Pagination<T> {
	data: T[];
	firstPageUrl: string;
	lastPageUrl: string;
	prevPageUrl: string;
	nextPageUrl: string;
	totalPage: number;
	pageSize: number;
	pageIndex: number;
	lastPage: number;
	links: PaginationLink[];
}

export interface PropsData extends PageProps {
	users: Pagination<User>;
}

const pagination = reactive<Pagination<User>>({
	data: [],
	firstPageUrl: '',
	lastPageUrl: '',
	prevPageUrl: '',
	nextPageUrl: '',
	totalPage: -1,
	pageSize: 10,
	pageIndex: 1,
	lastPage: 1,
	links: [],
});

const isFetching = ref(false);
const selectedRows = computed(() => table.getSelectedRowModel().rows.map(row => row.original));
const userTableStore = useUserTableStore();

onMounted(async () => {
	await fetchData();
})

const table = useVueTable({
	manualPagination: true,
	manualFiltering: true,
	manualSorting: true,
	get data() { return pagination.data },
	get columns() { return columns },
	get rowCount() { return pagination.totalPage },
	state: {
		get sorting() { return userTableStore.sorting },
		get columnFilters() { return userTableStore.columnFilters },
		get globalFilter() { return userTableStore.globalFilter },
		get columnVisibility() { return userTableStore.columnVisibility },
		get rowSelection() { return userTableStore.rowSelection },
		get expanded() { return userTableStore.expanded },
		get pagination() { return userTableStore.pagination },
	},
	onSortingChange: userTableStore.setSorting,
	onColumnFiltersChange: userTableStore.setColumnFilters,
	onGlobalFilterChange: userTableStore.setGlobalFilter,
	onColumnVisibilityChange: userTableStore.setColumnVisibility,
	onRowSelectionChange: userTableStore.setRowSelection,
	onExpandedChange: userTableStore.setExpanded,
	onPaginationChange: userTableStore.setPagination,
	getCoreRowModel: getCoreRowModel(),
	getSortedRowModel: getSortedRowModel(),
	getFilteredRowModel: getFilteredRowModel(),
	getExpandedRowModel: getExpandedRowModel(),
	getPaginationRowModel: getPaginationRowModel(),
})

async function fetchData() {
	isFetching.value = true
	try {
		const response = await axios.get<UsersResponse<User>>(route('users.datatable'), {
			params: {
				page: table.getState().pagination.pageIndex + 1,
				per_page: table.getState().pagination.pageSize,
				search: table.getState().globalFilter,
				sorting: table.getState().sorting[0]?.id,
				direction: table.getState().sorting[0]?.desc ? 'desc' : 'asc',
				...userTableStore.columnFilters,
			},
		});

		const result = response.data;
		pagination.data = result.data;
		pagination.firstPageUrl = result.first_page_url;
		pagination.lastPageUrl = result.last_page_url;
		pagination.prevPageUrl = result.prev_page_url;
		pagination.nextPageUrl = result.next_page_url;
		pagination.totalPage = result.total;
		pagination.pageSize = result.per_page;
		pagination.pageIndex = result.current_page;
		pagination.lastPage = result.last_page;
		pagination.links = result.links;

		isFetching.value = false
	} catch (err) {
		console.error(err)
		isFetching.value = false
	}
}

async function debounceFilter() {
	table.setPageIndex(0);
	await fetchData();
}

watch([
	() => table.getState().pagination.pageIndex,
	() => table.getState().pagination.pageSize,
], fetchData);

watch(() => table.getState().globalFilter, useDebounceFn(debounceFilter, 800));
watch(() => table.getState().sorting, useDebounceFn(fetchData, 500));

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
			<!-- <Input class="max-w-sm" placeholder="Filter emails..." :model-value="table.getColumn('email')?.getFilterValue() as string" @update:model-value="table.getColumn('email')?.setFilterValue($event)" /> -->
			<Input type="search" class="max-w-sm" placeholder="Search..." :model-value="table.getState().globalFilter" @update:model-value="table.setGlobalFilter($event)"  />
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
				<template v-if="table.getRowModel().rows?.length ">
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
							<span v-if="isFetching">Fetching...</span>
							<span v-else>No results.</span>
						</TableCell>
					</TableRow>
				</template>
			</TableBody>
		</Table>
	</div>
	<div class="flex items-start justify-end space-x-2">
		<div class="flex-1 text-sm text-muted-foreground py-2">
			{{ table.getFilteredSelectedRowModel().rows.length }} of
			{{ table.getFilteredRowModel().rows.length }} row(s) selected.
		</div>
		<Pagination class="space-x-2" :table="table" :pagination="pagination" @pageChanged="handlePageChanged" />
	</div>
</template>