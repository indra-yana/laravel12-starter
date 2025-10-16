import { ActionMeta } from './RecapTable.vue';
import { ArrowDownZA, ArrowUpAZ, ArrowUpDown } from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox';
import { ColumnConfig, LogbookRecap } from '@/types';
import { h } from 'vue';
import Actions from './Actions.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import type { ColumnDef, } from '@tanstack/vue-table';

export const availableFilters: Record<string, ColumnConfig> = {
    id: {
        key: 'id',
        label: 'Id',
    },
    name: {
        key: 'name',
        label: 'Name',
    },
    nip: {
        key: 'nip',
        label: 'NIP',
    },
    employment_status: {
        key: 'employment_status',
        label: 'Status Pegawai',
    },
    rank_code: {
        key: 'rank_code',
        label: 'Golongan Ruang',
    },
    rank_title: {
        key: 'rank_title',
        label: 'Pangkat',
    },
    work_unit: {
        key: 'work_unit',
        label: 'Satuan Kerja',
    },
    month: {
        key: 'month',
        label: 'Bulan',
    },
    status: {
        key: 'status',
        label: 'Status',
        mapper: {
            'Selesai': 'done',
            'Belum': 'pending',
        }
    },
};

export const columns: ColumnDef<LogbookRecap>[] = [
    {
        id: 'select',
        header: ({ table }) => h(Checkbox, {
            'modelValue': table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
            'onUpdate:modelValue': value => table.toggleAllPageRowsSelected(!!value),
            'ariaLabel': 'Select all',
        }),
        cell: ({ row }) => h(Checkbox, {
            'modelValue': row.getIsSelected(),
            'onUpdate:modelValue': value => row.toggleSelected(!!value),
            'ariaLabel': 'Select row',
        }),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: availableFilters.name.key,
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            availableFilters.name.label,
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => {
            const name = row.getValue('name') as string;

            return h('div', { class: 'text-left font-medium' }, name)
        },
    },
    {
        accessorKey: availableFilters.employment_status.key,
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            availableFilters.employment_status.label,
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('employment_status')),
    },
    {
        accessorKey: availableFilters.rank_code.key,
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            availableFilters.rank_code.label,
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('rank_code')),
    },
    {
        accessorKey: availableFilters.rank_title.key,
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            availableFilters.rank_title.label,
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('rank_title')),
    },
    {
        accessorKey: availableFilters.work_unit.key,
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            availableFilters.work_unit.label,
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('work_unit')),
    },
    // {
    //     accessorKey: availableFilters.month.key,
    //     enableSorting: true,
    //     header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
    //         variant: column.getIsSorted() ? 'outline' : 'ghost',
    //         onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
    //     }, () => [
    //         availableFilters.month.label,
    //         column.getIsSorted() === 'asc'
    //             ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
    //                 : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
    //     ]
    //     )),
    //     cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('month')),
    // },
    {
        accessorKey: availableFilters.status.key,
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            availableFilters.status.label,
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => {
            const status = row.original.status == 'done' ? 'Selesai' : 'Belum';

            return h('div', { class: 'text-center font-medium' }, h(Badge, {
                variant: status === 'Selesai' ? 'success' : 'destructive',
            }, () => status))
        },
    },
    {
        id: 'actions',
        enableSorting: false,
        enableHiding: false,
        enableGlobalFilter: false,
        header: () => h('div', { class: 'text-center' }, 'Actions'),
        cell: ({ row, table }) => {
            const recap = row.original;
            const meta = table.options.meta as ActionMeta;

            return h('div', { class: 'text-center relative' }, h(Actions, {
                recap,
                onExpand: row.toggleExpanded,
                onDelete: (recap: LogbookRecap) => meta?.onDelete(recap),
                onEdit: (recap: LogbookRecap) => meta?.onEdit(recap),
            }))
        },
    },
]