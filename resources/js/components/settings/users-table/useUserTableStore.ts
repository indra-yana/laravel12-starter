import { defineStore } from 'pinia';
import { ref } from 'vue';
import { valueUpdater } from '@/components/ui/table/utils';
import type { SortingState, ColumnFiltersState, VisibilityState, RowSelectionState, ExpandedState, PaginationState, Updater, } from '@tanstack/table-core';

export const useUserTableStore = defineStore('userTable', () => {
	const sorting = ref<SortingState>([]);
	const columnFilters = ref<ColumnFiltersState>([]);
	const columnVisibility = ref<VisibilityState>({});
	const rowSelection = ref<RowSelectionState>({});
	const expanded = ref<ExpandedState>({});
	const pagination = ref<PaginationState>({
		pageIndex: 0,
		pageSize: 10,
	});

	function setSorting(updater: Updater<SortingState>) {
		return valueUpdater(updater, sorting);
	}

	function setColumnFilters(updater: Updater<ColumnFiltersState>) {
		return valueUpdater(updater, columnFilters);
	}

	function setColumnVisibility(updater: Updater<VisibilityState>) {
		return valueUpdater(updater, columnVisibility);
	}

	function setRowSelection(updater: Updater<RowSelectionState>) {
		return valueUpdater(updater, rowSelection);
	}

	function setExpanded(updater: Updater<ExpandedState>) {
		return valueUpdater(updater, expanded);
	}

	function setPagination(updater: Updater<PaginationState>) {
		return valueUpdater(updater, pagination);
	}

	return {
		sorting,
		columnFilters,
		columnVisibility,
		rowSelection,
		expanded,
		pagination,
		setSorting,
		setColumnFilters,
		setColumnVisibility,
		setRowSelection,
		setExpanded,
		setPagination,
	};
}, {
	persist: true,
});
