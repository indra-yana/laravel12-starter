<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ref, computed, watch } from 'vue';
import PermissionGroup from './PermissionGroup.vue';

interface Props {
    permissions: Record<string, any[]>
};

const props = defineProps<Props>();
// TODO: Ambil value dari user
const selected = ref<number[]>([]);
const allPermissionIds = computed(() => Object.values(props.permissions).flat().map(p => p.id))
const isAllSelected = computed(() => allPermissionIds.value.every(id => selected.value.includes(id)))

function toggleSelectAll() {
    selected.value = isAllSelected.value ? [] : [...allPermissionIds.value]
}

// TODO: Async call to assign permissions
watch(selected, () => {
    console.log(selected.value);
});

</script>

<template>
    <div class="space-y-4">
        <div class="flex justify-between items-center border-b pb-2">
            <h1 class="text-xl font-semibold">Manage Permissions</h1>
            <Button variant="outline" @click="toggleSelectAll">
                {{ isAllSelected ? 'Unselect All' : 'Select All' }}
            </Button>
        </div>

        <div v-for="(items, group) in permissions" :key="group">
            <PermissionGroup :group="group" :permissions="items" v-model:selected="selected" />
        </div>
    </div>
</template>
