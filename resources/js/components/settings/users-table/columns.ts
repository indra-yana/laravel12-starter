import { ArrowUpDown } from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox';
import { h } from 'vue';
import { User } from '@/types';
import Actions from './Actions.vue';
import Button from '@/components/ui/button/Button.vue';
import type { ColumnDef, } from '@tanstack/vue-table';

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
            variant: 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]
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
            variant: 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]
        )),
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('email')),
    },
    {
        accessorKey: 'status',
        enableSorting: true,
        header: ({ column }) => h('div', { class: 'text-center' }, h(Button, {
            variant: 'ghost',
            onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
        }, () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]
        )),
        cell: ({ row }) => {
            const status = row.getValue('status') as string;

            return h('div', { class: 'text-center font-medium' }, status)
        },
    },
    {
        id: 'actions',
        enableSorting: false,
        enableHiding: false,
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