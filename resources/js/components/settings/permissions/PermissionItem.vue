<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';

interface Props {
    permission: {
        id: number,
        name: string,
        group_name: string
    }
    checked: boolean
};

defineProps<Props>();

const emit = defineEmits(['toggle'])

function toTitleCase(str: string): string {
    return str
        .replace(/\./g, ' ')
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join('.');
}

function formatPermissionLabel(name: string): string {
    const parts = name.split('.');
    const last = parts[parts.length - 1];

    return last.charAt(0).toUpperCase() + last.slice(1)
}

</script>

<template>
    <div class="flex items-center gap-2 px-2 py-1 border rounded-md bg-secondary">
        <Label :for="`toggle-all-${permission.name}`" class="flex items-center">
            <Checkbox :id="`toggle-all-${permission.name}`" :model-value="checked" @update:model-value="emit('toggle', $event)" />
            <span class="text-sm">{{ formatPermissionLabel(toTitleCase(permission.name)) }}</span>
        </Label>
    </div>
</template>
