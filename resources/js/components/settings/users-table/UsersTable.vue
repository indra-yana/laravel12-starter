<script setup lang="ts" generic="TData, TValue">
import 'vue-json-pretty/lib/styles.css';
import { availableFilters, columns } from './columns';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { DeleteIcon, EyeIcon, Filter, WrenchIcon, XCircleIcon, XIcon } from 'lucide-vue-next';
import { FlexRender, getCoreRowModel, getExpandedRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, Updater, useVueTable, } from '@tanstack/vue-table';
import { router, useForm } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { useDebounceFn } from '@vueuse/core';
import { User } from '@/types';
import { useUserTableStore } from './useUserTableStore';
import axios from 'axios';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuCheckboxItem from '@/components/ui/dropdown-menu/DropdownMenuCheckboxItem.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuLabel from '@/components/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/DropdownMenuSeparator.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import Input from '@/components/ui/input/Input.vue';
import PageLength from '@/components/settings/users-table/PageLength.vue';
import Pagination from './Pagination.vue';
import type { PageProps } from '@inertiajs/core';
import VueJsonPretty from 'vue-json-pretty';

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

export interface ActionMeta {
	onDelete: (user: User) => void,
	onEdit: (user: User) => void,
	onAssignRole: (user_id: number) => void,
	onAssignPermission: (user_id: number) => void,
}

interface ActionPayload {
	ids: number[],
	[key: string]: any
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
const showConfirm = ref(false);
const form = useForm<ActionPayload>({
	ids: [],
	names: [],
});
const selectedNames = computed(() => form.names.join('<br>'));
const table = useVueTable({
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
	meta: {
		onDelete: handleDelete,
		onEdit: handleEdit,
		onAssignRole: handleOnAssignRole,
		onAssignPermission: handleOnAssignPermission,
	},
	manualPagination: true,
	manualFiltering: true,
	manualSorting: true,
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

onMounted(async () => {
	await fetchData();
})

async function fetchData() {
	isFetching.value = true;
	try {
		const response = await axios.get<UsersResponse<User>>(route('users.datatable'), {
			params: {
				page: table.getState().pagination.pageIndex + 1,
				per_page: table.getState().pagination.pageSize,
				search: table.getState().globalFilter,
				sorting: table.getState().sorting[0]?.id,
				direction: table.getState().sorting[0]?.desc ? 'desc' : 'asc',
				column_filters: table.getState().columnFilters,
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

		isFetching.value = false;
	} catch (err) {
		console.error(err)
		isFetching.value = false;
	}
}

async function debounceGlobalFilter() {
	table.setPageIndex(0);
	userTableStore.setColumnFilters(
		userTableStore.activeFilters.map((key) => ({ id: key, value: handleFilterMapper(key, table.getState().globalFilter) }))
	);

	await fetchData();
}

watch([
	() => table.getState().pagination.pageIndex,
	() => table.getState().pagination.pageSize,
], fetchData);

watch(() => table.getState().globalFilter, useDebounceFn(debounceGlobalFilter, 800));
watch(() => table.getState().columnFilters, useDebounceFn(fetchData, 500));
watch(() => table.getState().sorting, useDebounceFn(fetchData, 500));

const handlePageChanged = (newIndex: number) => {
	table.setPageIndex(newIndex);
}

function onActiveFilterUpdate(filterKey: string, checked: boolean) {
	const filters: Updater<string[]> = userTableStore.activeFilters;
	if (checked && !filters.includes(filterKey)) {
		filters.push(filterKey);
	} else {
		const index = filters.indexOf(filterKey);
		if (index !== -1) {
			filters.splice(index, 1)
		};
	}

	userTableStore.setActiveFilters(filters);
	userTableStore.setColumnFilters(
		filters.map((key) => ({ id: key, value: handleFilterMapper(key, table.getState().globalFilter) }))
	);
}

function handleFilterMapper(filterKey: string, value: any) {
	if (availableFilters[filterKey]?.mapper !== undefined) {
		return availableFilters[filterKey]?.mapper[value?.toLowerCase()] ?? value;
	}

	return value;
}

function handleBulkDelete() {
	form.ids = selectedRows.value.map((user) => user.id);
	form.names = selectedRows.value.map((user) => user.name);

	handleConfirm(true);
}

function handleDelete(user: User) {
	form.ids = [user.id];
	form.names = [user.name];
	handleConfirm(true);
}

function handleEdit(user: User) {
	console.log('handleEdit', user);
}

function handleOnAssignRole(userId: number) {
	router.visit(route('roles.index', { user_id: userId }));
}

function handleOnAssignPermission(userId: number) {
	router.visit(route('permissions.index', { user_id: userId }));
}

function postDelete() {
	form.delete(route('users.destroy'), {
		preserveScroll: true,
		preserveState: ({ props }) => !!Object.keys(props.errors).length,
		onError: () => handleConfirm(false),
		onSuccess: () => {
			form.reset();
			table.setRowSelection({});
			handleConfirm(false);
		},
	})
}

function handleConfirm(show: boolean) {
	showConfirm.value = show;
}

</script>

<template>
	<ConfirmDialog type="delete" :open="showConfirm" :onConfirm="postDelete" :onCancel="() => handleConfirm(false)" :loading="form.processing" title="Delete users?" description="This action will permanently delete the selected users." :detail="selectedNames"></ConfirmDialog>

	<div class="flex flex-col md:flex-row items-start md:items-center md:justify-between mt-4">
		<div class="w-full sm:w-auto md:flex-1">
			<!-- <Input class="max-w-sm" placeholder="Filter emails..." :model-value="table.getColumn('email')?.getFilterValue() as string" @update:model-value="table.getColumn('email')?.setFilterValue($event)" /> -->
			<Input type="search" class="max-w-sm" placeholder="Search..." :model-value="table.getState().globalFilter" @update:model-value="table.setGlobalFilter($event)" />
		</div>
		<div class="flex flex-row items-start justify-between space-x-2 mt-2 md:mt-0 ">
			<DropdownMenu>
				<DropdownMenuTrigger as-child>
					<Button variant="outline" class="ml-auto">
						<Filter class="size-4 mr-2" />
						Filter
					</Button>
				</DropdownMenuTrigger>
				<DropdownMenuContent align="end" class="min-w-[200px]">
					<DropdownMenuLabel>Filter Columns</DropdownMenuLabel>
					<DropdownMenuSeparator />
					<DropdownMenuCheckboxItem v-for="(filter, key) in availableFilters" :key="key" :modelValue="userTableStore.activeFilters.includes(filter.key)" @update:modelValue="(checked) => onActiveFilterUpdate(filter.key, checked)" @select="(event) => event.preventDefault()">
						{{ filter.label }}
					</DropdownMenuCheckboxItem>
				</DropdownMenuContent>
			</DropdownMenu>
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
					<Button variant="ghost" class="ml-auto text-destructive" size="sm" @click="handleBulkDelete" :disabled="!selectedRows.length">
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
	<div class="flex flex-wrap gap-2 ">
		<Badge variant="secondary" class="flex items-center gap-2 px-2 py-1 shadow-sm" v-for="key in userTableStore.activeFilters" :key="key">
			<span>{{ availableFilters[key]?.label }}</span>
			<button class="hover:text-destructive" @click="onActiveFilterUpdate(key, false)">
				<XIcon class="size-3" title="Remove" />
			</button>
		</Badge>
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
								<div class="relative whitespace-pre-wrap bg-secondary p-4 rounded-lg overflow-x-auto max-w-full">
									<Button variant="link" size="icon" class="absolute top-2 right-2 z-10 rounded-full" @click="() => row.toggleExpanded(!row.getIsExpanded())">
										<XCircleIcon class="size-6" />
									</Button>
									<VueJsonPretty :data="row.original" :showIcon="true" :showLineNumber="true" :showLength="true" />
								</div>
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
		<PageLength :table="table" />
		<Pagination class="space-x-2" :table="table" :pagination="pagination" @pageChanged="handlePageChanged" />
	</div>
</template>