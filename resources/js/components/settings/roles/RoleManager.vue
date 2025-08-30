<script setup lang="ts">
import { ref, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';
import { useForm } from '@inertiajs/vue3';
import { User } from '@/types';
import RoleSelect from './RoleSelect.vue';

export interface RoleDef {
    id: number,
    name: string,
}

export interface Role {
    role: string | "Super Admin" | "Admin" | "User";
    permissions: string[];
}

interface Props {
    user?: User | null;
    selectedRoles: RoleDef[];
    roles: Role[];
};

const props = defineProps<Props>();
const selectedRole = ref<string>(props.selectedRoles.length > 0 ? props.selectedRoles[0].name : "");
const form = useForm({
    user_id: props?.user?.id,
    role: selectedRole.value,
});

function postSelected() {
    form.role = selectedRole.value;
    form.post(route('roles.assign'), {
        preserveScroll: true,
        preserveState: ({ props }) => !!Object.keys(props.errors).length,
        onSuccess: () => {

        },
    });
}

watch(() => selectedRole.value, useDebounceFn(postSelected, 500));

</script>

<template>
    <div class="space-y-4">
        <div class="flex justify-between items-center border-b pb-2">
            <div class="flex-col">
                <template v-if="!user">
                    <h1>No user was chosen!</h1>
                </template>
                <template v-else>
                    <div>
                        <span class=" text-muted-foreground">Assign <b>{{ selectedRole || '' }}</b> role to:</span> <b>{{ user?.name }}</b>
                    </div>
                </template>
            </div>
        </div>

        <template v-if="user">
            <RoleSelect v-model="selectedRole" :roles="roles" />
        </template>
    </div>
</template>
