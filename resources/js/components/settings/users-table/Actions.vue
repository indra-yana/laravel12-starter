<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { DeleteIcon, EditIcon, MoreHorizontal } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { User } from '@/types';

interface Props {
    user: User
}

interface Emits {
    (e: 'expand'): void,
    (e: 'edit', user: User): void,
    (e: 'delete', user: User): void,
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

function assignPermission(userId: number) {
    emits('assignPermission', userId);
}
</script>

<template>
    <div class="space-x-2">
        <Button variant="default" size="sm" @click="edit(user)">
            <EditIcon class="size-4" /> Edit
        </Button>
        <Button variant="destructive" size="sm" @click="destroy(user)">
            <DeleteIcon class="size-4" /> Delete
        </Button>
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button variant="ghost" class="w-8 h-8 p-0">
                    <span class="sr-only">Open menu</span>
                    <MoreHorizontal class="w-4 h-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuLabel>Other Actions</DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="$emit('expand')">Expand Row</DropdownMenuItem>
                <DropdownMenuItem @click="assignPermission(user.id)">Assign Permissions</DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>