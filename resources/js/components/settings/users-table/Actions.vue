<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { Edit, MoreHorizontal, Trash2 } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { User } from '@/types';

interface Props {
    user: User
}

interface Emits {
    (e: 'expand'): void,
    (e: 'edit', user: User): void,
    (e: 'delete', user: User): void,
    (e: 'assignRole', userId: number): void,
    (e: 'assignPermission', userId: number): void,
}

defineProps<Props>();

const emits = defineEmits<Emits>();

function edit(user: User) {
    emits('edit', user);
}

function destroy(user: User) {
    emits('delete', user);
}

function assignRole(userId: number) {
    emits('assignRole', userId);
}

function assignPermission(userId: number) {
    emits('assignPermission', userId);
}
</script>

<template>
    <div class="space-x-2">
        <Button variant="default" size="sm" @click="edit(user)">
            <Edit class="size-4" /> {{ trans('label.edit') }}
        </Button>
        <Button variant="destructive" size="sm" @click="destroy(user)">
            <Trash2 class="size-4" /> {{ trans('label.delete') }}
        </Button>
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button variant="ghost" class="w-8 h-8 p-0">
                    <span class="sr-only">Open menu</span>
                    <MoreHorizontal class="w-4 h-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuLabel>{{ trans('label.other_actions') }}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="$emit('expand')">{{ trans('label.expand_row') }}</DropdownMenuItem>
                <DropdownMenuItem @click="assignRole(user.id)">{{ trans('label.assign_role') }}</DropdownMenuItem>
                <DropdownMenuItem @click="assignPermission(user.id)">{{ trans('label.assign_permissions') }}</DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>