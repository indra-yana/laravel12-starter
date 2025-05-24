<script setup lang="ts">
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger, } from '@/components/ui/accordion';
import { Checkbox } from '@/components/ui/checkbox';
import { computed, ref } from 'vue';
import { Label } from '@/components/ui/label';
import PermissionItem from './PermissionItem.vue';

interface Props {
    group: string
    permissions: { 
        id: number, 
        name: string, 
        group_name: string 
    }[]
    selected: number[]
};

const emit = defineEmits(['update:selected']);
const props = defineProps<Props>();
const isOpen = ref(true);
const permissionIds = computed(() => props.permissions.map(p => p.id));
const groupSelectedCount = computed(() => permissionIds.value.filter(id => props.selected.includes(id)).length);
const isGroupSelected = computed(() => groupSelectedCount.value === permissionIds.value.length);
const isIndeterminate = computed(() => groupSelectedCount.value > 0 && !isGroupSelected.value);

function toggleGroup(checked: boolean) {
    const current = new Set(props.selected);
    if (checked) {
        permissionIds.value.forEach(id => current.add(id));
    } else {
        permissionIds.value.forEach(id => current.delete(id));
    }

    emit('update:selected', Array.from(current));
}

function toggleItem(id: number, checked: boolean) {
    const current = new Set(props.selected);
    if (checked) {
        current.add(id);
    } else {
        current.delete(id);
    }

    emit('update:selected', Array.from(current));
}
</script>

<template>
    <Accordion type="multiple" collapsible :default-value="[group]">
        <AccordionItem :value="group" class="rounded-md">
            <div class="flex items-center justify-between px-3 py-2 bg-secondary border rounded-t-md" :class="isOpen ? '' : 'rounded-b-md'">
                <Label for="toggle-all" class="flex items-center">
                    <Checkbox id="toggle-all" :model-value="isGroupSelected" :indeterminate="isIndeterminate" @update:model-value="(toggleGroup as any)" />
                    <span class="font-semibold text-sm uppercase">{{ group }}</span>
                </Label>
                <AccordionTrigger class="p-1 hover:bg-primary rounded-full shadow" @click="isOpen = !isOpen" />
            </div>

            <AccordionContent class="px-3 py-3 border rounded-b-md">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <PermissionItem v-for="item in permissions" :key="item.id" :permission="item" :checked="selected.includes(item.id)" @toggle="checked => toggleItem(item.id, checked)" />
                </div>
            </AccordionContent>
        </AccordionItem>
    </Accordion>
</template>
