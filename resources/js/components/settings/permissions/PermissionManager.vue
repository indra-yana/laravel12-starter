<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ref, computed, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';
import { useForm } from '@inertiajs/vue3';
import { User } from '@/types';
import PermissionGroup from '@/components/settings/permissions/PermissionGroup.vue';

export interface Permission {
    id: number,
    name: string,
    group_name: string
}

interface Props {
    user?: User | null
    selectedPermissions: string[]
    permissions: Record<string, Permission[]>
};

const props = defineProps<Props>();
const selected = ref<string[]>(props.selectedPermissions || []);
const allPermissionNames = computed(() => Object.values(props.permissions).flat().map(p => p.name))
const isAllSelected = computed(() => allPermissionNames.value.every(name => selected.value.includes(name)))
const form = useForm({
    user_id: props?.user?.id,
    permissions: selected.value,
});

function toggleSelectAll() {
    selected.value = isAllSelected.value ? [] : [...allPermissionNames.value]
}

function postSelected() {
    form.permissions = selected.value;
    form.post(route('permissions.assign'), {
        preserveScroll: true,
        preserveState: ({ props }) => !!Object.keys(props.errors).length,
        onSuccess: () => {

        },
    });
}

watch(selected, useDebounceFn(postSelected, 500));

</script>

<template>
    <div class="space-y-4 space-x-4">
        <div class="flex justify-between items-center border-b pb-2">
            <div>
                <template v-if="!user">
                    <h1>No user was chosen!</h1>
                </template>
                <template v-else>
                    <div>
                        <span class=" text-muted-foreground">Assign permissions to:</span> <b>{{ user?.name }}</b>
                    </div>
                </template>
            </div>
            <Button variant="outline" @click="toggleSelectAll" v-if="user">
                {{ isAllSelected ? 'Unselect All' : 'Select All' }}
            </Button>
        </div>

        <template v-if="user">
            <div v-for="(items, group) in permissions" :key="group">
                <PermissionGroup :group="group" :permissions="items" v-model:selected="selected" />
            </div>
        </template>
    </div>
</template>
