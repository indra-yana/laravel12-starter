import { defineStore } from 'pinia';
import { ref } from 'vue';
import { valueUpdater } from '@/components/ui/table/utils';
import type { SortingState, ColumnFiltersState, VisibilityState, RowSelectionState, ExpandedState, PaginationState, Updater, } from '@tanstack/table-core';

export const useRecapTableStore = defineStore('logbookRecapTable', () => {
	const sorting = ref<SortingState>([{
		desc: false,
		id: 'name',
	}]);
	const columnFilters = ref<ColumnFiltersState>([]);
	const activeFilters = ref<string[]>([]);
	const globalFilter = ref<string | null>(null);
	const columnVisibility = ref<VisibilityState>({});
	const rowSelection = ref<RowSelectionState>({});
	const expanded = ref<ExpandedState>({});
	const pagination = ref<PaginationState>({
		pageIndex: 0,
		pageSize: 10,
	});

	return {
		sorting,
		columnFilters,
		activeFilters,
		globalFilter,
		columnVisibility,
		rowSelection,
		expanded,
		pagination,
		setSorting: (updater: Updater<SortingState>) => valueUpdater(updater, sorting),
		setColumnFilters: (updater: Updater<ColumnFiltersState>) => valueUpdater(updater, columnFilters),
		setActiveFilters: (updater: Updater<string[]>) => valueUpdater(updater, activeFilters),
		setGlobalFilter: (updater: Updater<string | null>) => valueUpdater(updater, globalFilter),
		setColumnVisibility: (updater: Updater<VisibilityState>) => valueUpdater(updater, columnVisibility),
		setRowSelection: (updater: Updater<RowSelectionState>) => valueUpdater(updater, rowSelection),
		setExpanded: (updater: Updater<ExpandedState>) => valueUpdater(updater, expanded),
		setPagination: (updater: Updater<PaginationState>) => valueUpdater(updater, pagination),
	};
}, {
	persist: true,
});
