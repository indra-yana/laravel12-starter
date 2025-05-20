<script setup lang="ts" generic="TData, TValue">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { DeleteIcon, EditIcon, MoreHorizontal } from 'lucide-vue-next';
import { User } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';

interface Props {
    user: User
}

interface DeleteUsersPayload {
    ids: number[],
    [key: string]: any
}

interface Emits {
    (e: 'expand'): void,
    (e: 'edit', user: User): void,
    (e: 'delete', id: number[]): void,
}

defineProps<Props>();

const emits = defineEmits<Emits>();
const form = useForm<DeleteUsersPayload>({
    ids: [],
});

const showConfirm = ref(false);

function edit(user: User) {
    emits('edit', user);
}

function destroy() {
    emits('delete', form.ids);
    form.delete(route('users.destroy'), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            form.reset();
            handleConfirm();
        },
    })
}

function handleDeleteConfirm(id: number) {
    form.ids = [id];
    handleConfirm()
}

function handleConfirm() {
  showConfirm.value = true;
}

function handleCancel() {
  showConfirm.value = false
}

function setPermission(id: number) {
    console.log(id);
}
</script>

<template>
    <DeleteConfirmDialog
        :open="showConfirm"
        :onConfirm="destroy"
        :onCancel="handleCancel"
        :loading="form.processing"
        title="Delete users?"
        description="This action will permanently delete the selected users."
    >
        <template #trigger>
            <!-- if you want trigger inside dialog -->
        </template>
    </DeleteConfirmDialog>

    <div class="space-x-2">
        <Button variant="default" size="sm" @click="edit(user)">
            <EditIcon class="size-4" /> Edit
        </Button>
        <Button variant="destructive" size="sm" @click="handleDeleteConfirm(user.id)">
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
                <DropdownMenuItem @click="setPermission(user.id)">Assign Permissions</DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>