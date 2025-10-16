<script setup lang="ts" generic="TData, TValue">
import 'vue-json-pretty/lib/styles.css';
import { availableFilters, columns } from './columns';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { DeleteIcon, EyeIcon, Filter, WrenchIcon, XCircleIcon, XIcon } from 'lucide-vue-next';
import { FlexRender, getCoreRowModel, getExpandedRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, Updater, useVueTable, } from '@tanstack/vue-table';
import { LogbookRecap, PaginationResponse, PaginationType } from '@/types';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { useDebounceFn } from '@vueuse/core';
import { useForm } from '@inertiajs/vue3';
import { useRecapTableStore } from './useRecapTableStore';
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
import TableFooter from '@/components/base-table/TableFooter.vue';
import type { PageProps } from '@inertiajs/core';
import VueJsonPretty from 'vue-json-pretty';

export interface PropsData extends PageProps {
	recap: PaginationType<LogbookRecap>;
}

export interface ActionMeta {
	onDelete: (user: LogbookRecap) => void,
	onEdit: (user: LogbookRecap) => void,
}

interface ActionPayload {
	ids: number[],
	[key: string]: any
}

interface Emits {
	(e: 'edit', user: LogbookRecap): void,
}

const emits = defineEmits<Emits>();
const pagination = reactive<PaginationType<LogbookRecap>>({
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
const recapTableStore = useRecapTableStore();
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
		get sorting() { return recapTableStore.sorting },
		get columnFilters() { return recapTableStore.columnFilters },
		get globalFilter() { return recapTableStore.globalFilter },
		get columnVisibility() { return recapTableStore.columnVisibility },
		get rowSelection() { return recapTableStore.rowSelection },
		get expanded() { return recapTableStore.expanded },
		get pagination() { return recapTableStore.pagination },
	},
	meta: {
		onDelete: handleDelete,
		onEdit: handleEdit,
	},
	manualPagination: true,
	manualFiltering: true,
	manualSorting: true,
	onSortingChange: recapTableStore.setSorting,
	onColumnFiltersChange: recapTableStore.setColumnFilters,
	onGlobalFilterChange: recapTableStore.setGlobalFilter,
	onColumnVisibilityChange: recapTableStore.setColumnVisibility,
	onRowSelectionChange: recapTableStore.setRowSelection,
	onExpandedChange: recapTableStore.setExpanded,
	onPaginationChange: recapTableStore.setPagination,
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
		const response = await axios.get<PaginationResponse<LogbookRecap>>(route('logbook.racapitulation.datatable'), {
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
		Object.assign(pagination, {
			data: result.data,
			firstPageUrl: result.first_page_url,
			lastPageUrl: result.last_page_url,
			prevPageUrl: result.prev_page_url,
			nextPageUrl: result.next_page_url,
			totalPage: result.total,
			pageSize: result.per_page,
			pageIndex: result.current_page,
			lastPage: result.last_page,
			links: result.links,
		});
	} catch (err) {
		console.error(err);
	} finally {
		isFetching.value = false;
	}
}

async function debounceGlobalFilter() {
	table.setPageIndex(0);
	recapTableStore.setColumnFilters(
		recapTableStore.activeFilters.map((key) => ({ id: key, value: handleFilterMapper(key, table.getState().globalFilter) }))
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
	const filters: Updater<string[]> = recapTableStore.activeFilters;
	if (checked && !filters.includes(filterKey)) {
		filters.push(filterKey);
	} else {
		const index = filters.indexOf(filterKey);
		if (index !== -1) {
			filters.splice(index, 1)
		};
	}

	recapTableStore.setActiveFilters(filters);
	recapTableStore.setColumnFilters(
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

function handleDelete(user: LogbookRecap) {
	form.ids = [user.id];
	form.names = [user.name];
	handleConfirm(true);
}

function handleEdit(user: LogbookRecap) {
	emits('edit', user);
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
	<ConfirmDialog type="delete" :open="showConfirm" :onConfirm="postDelete" :onCancel="() => handleConfirm(false)" :loading="form.processing" :title="trans('label.delete_user')" :description="trans('label.delete_data_description')" :detail="selectedNames"></ConfirmDialog>

	<div class="flex flex-col md:flex-row items-start md:items-center md:justify-between mt-4">
		<div class="w-full sm:w-auto md:flex-1">
			<Input type="search" class="max-w-sm" :placeholder="trans('label.search...')" :model-value="table.getState().globalFilter" @update:model-value="table.setGlobalFilter($event)" />
		</div>
		<div class="flex flex-row items-start justify-between space-x-2 mt-2 md:mt-0 ">
			<DropdownMenu>
				<DropdownMenuTrigger as-child>
					<Button variant="outline" class="ml-auto">
						<Filter class="size-4 mr-2" />
						{{ trans('label.filter') }}
					</Button>
				</DropdownMenuTrigger>
				<DropdownMenuContent align="end" class="min-w-[200px]">
					<DropdownMenuLabel>{{ trans('label.filter_columns') }}</DropdownMenuLabel>
					<DropdownMenuSeparator />
					<DropdownMenuCheckboxItem v-for="(filter, key) in availableFilters" :key="key" :modelValue="recapTableStore.activeFilters.includes(filter.key)" @update:modelValue="(checked) => onActiveFilterUpdate(filter.key, checked)" @select="(event) => event.preventDefault()">
						{{ filter.label }}
					</DropdownMenuCheckboxItem>
				</DropdownMenuContent>
			</DropdownMenu>
			<DropdownMenu>
				<DropdownMenuTrigger as-child>
					<Button variant="outline" class="ml-auto">
						<WrenchIcon class="size-4" />
						{{ trans('label.actions') }}
					</Button>
				</DropdownMenuTrigger>
				<DropdownMenuContent align="end">
					<DropdownMenuLabel>{{ trans('label.bulk_actions') }}</DropdownMenuLabel>
					<DropdownMenuSeparator />
					<Button variant="ghost" class="ml-auto text-destructive" size="sm" @click="handleBulkDelete" :disabled="!selectedRows.length">
						<DeleteIcon class="size-4 me-2" />
						{{ trans('label.delete_selected') }}
					</Button>
				</DropdownMenuContent>
			</DropdownMenu>
			<DropdownMenu>
				<DropdownMenuTrigger as-child>
					<Button variant="outline" class="ml-auto">
						<EyeIcon class="size-4" />
						{{ trans('label.columns') }}
					</Button>
				</DropdownMenuTrigger>
				<DropdownMenuContent align="end">
					<DropdownMenuLabel>{{ trans('label.toggle_columns') }}</DropdownMenuLabel>
					<DropdownMenuSeparator />
					<DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((column) => column.getCanHide())" :key="column.id" class="capitalize" :modelValue="column.getIsVisible()" @update:modelValue="(value) => {
						column.toggleVisibility(!!value)
					}">
						{{ availableFilters[column.id]?.label }}
					</DropdownMenuCheckboxItem>
				</DropdownMenuContent>
			</DropdownMenu>
		</div>
	</div>
	<div class="flex flex-wrap gap-2 ">
		<Badge variant="secondary" class="flex items-center gap-2 px-2 py-1 shadow-sm" v-for="key in recapTableStore.activeFilters" :key="key">
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

	<TableFooter v-if="table" :table="table" :pagination="pagination" @pageChanged="handlePageChanged" />

</template>