<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { Edit, MoreHorizontal, Trash2 } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { LogbookRecap } from '@/types';

interface Props {
    recap: LogbookRecap
}

interface Emits {
    (e: 'expand'): void,
    (e: 'edit', recap: LogbookRecap): void,
    (e: 'delete', recap: LogbookRecap): void,
}

defineProps<Props>();

const emits = defineEmits<Emits>();

function edit(recap: LogbookRecap) {
    emits('edit', recap);
}

function destroy(recap: LogbookRecap) {
    emits('delete', recap);
}

</script>

<template>
    <div class="space-x-2">
        <Button variant="default" size="sm" @click="edit(recap)">
            <Edit class="size-4" /> {{ trans('label.edit') }}
        </Button>
        <Button variant="destructive" size="sm" @click="destroy(recap)">
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
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>