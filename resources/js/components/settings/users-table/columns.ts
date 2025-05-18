import { ArrowDownZA, ArrowUpAZ, ArrowUpDown } from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox';
import { h } from 'vue';
import { User } from '@/types';
import Actions from './Actions.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import type { ColumnDef, } from '@tanstack/vue-table';

export const availableFilters: Record<string, any> = {
    name: {
        key: 'name',
        label: 'Name',
    },
    email: {
        key: 'email',
        label: 'Email',
    },
    is_active: {
        key: 'is_active',
        label: 'Status',
        mapper: {
            'active': true,
            'inactive': false,
            '1': true,
            '0': false,
        }
    },
} as const;

export const columns: ColumnDef<User>[] = [
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
        accessorKey: 'name',
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            'Name',
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
        accessorKey: 'email',
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            'Email',
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('email')),
    },
    {
        accessorKey: 'is_active',
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: column.getIsSorted() ? 'outline' : 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => [
            'Status',
            column.getIsSorted() === 'asc'
                ? h(ArrowUpAZ, { class: 'ml-2 h-4 w-4' }) : column.getIsSorted() === 'desc' ? h(ArrowDownZA, { class: 'ml-2 h-4 w-4' })
                    : h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
        ]
        )),
        cell: ({ row }) => {
            const status = row.original.is_active ? 'Active' : 'Inactive';

            return h('div', { class: 'text-center font-medium' }, h(Badge, {
                variant: status === 'Active' ? 'default' : 'destructive',
            }, () => status))
        },
    },
    {
        id: 'actions',
        enableSorting: false,
        enableHiding: false,
        enableGlobalFilter: false,
        header: () => h('div', { class: 'text-center' }, 'Actions'),
        cell: ({ row }) => {
            const user = row.original;

            return h('div', { class: 'text-center relative' }, h(Actions, {
                user,
                onExpand: row.toggleExpanded,
            }))
        },
    },
]