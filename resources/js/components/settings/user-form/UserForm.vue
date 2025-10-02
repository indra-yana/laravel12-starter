<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ref, watch, computed } from 'vue';
import { SaveIcon, XCircleIcon } from 'lucide-vue-next';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle } from '@/components/ui/sheet';
import { useForm } from '@inertiajs/vue3';
import { User } from '@/types';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import InputError from '@/components/InputError.vue';

export type CurrentRow = Omit<User, 'id'> & {
    id?: number;
}

interface Props {
    open: boolean;
    title: string;
    description?: string;
    currentRow?: CurrentRow;
    onOpenChange: (open: boolean) => void;
}

const userForm = ref();
const props = defineProps<Props>();
const isUpdate = computed(() => !!props.currentRow?.id);
const showConfirm = ref(false);
const form = useForm({
    name: props.currentRow?.name || '',
    email: props.currentRow?.email || '',
    is_active: props.currentRow?.is_active ?? true,
});

watch(() => props.open, (open) => {
    if (!open) resetForm();
});

function handleSubmit() {
    if (userForm.value && userForm.value.reportValidity()) {
        showConfirm.value = true
    }
}

function submitConfirmed() {
    const url = isUpdate.value ? route('users.update') : route('users.store');
    const method = isUpdate.value ? 'put' : 'post';

    form.submit(method, url, {
        method,
        preserveScroll: true,
        preserveState: ({ props }) => !!Object.keys(props.errors).length,
        onError: () => handleCancel(),
        onSuccess: () => {
            props.onOpenChange(false);
            resetForm();
            handleCancel();
        },
    });
}

function toggleStatus(checked: boolean) {
    form.is_active = checked;
}

function handleCancel() {
    showConfirm.value = false
}

function resetForm() {
    form.reset();
    form.clearErrors();
}

</script>

<template>
    <Sheet :open="props.open" @update:open="props.onOpenChange">
        <SheetContent class="flex flex-col h-full">
            <SheetHeader class="text-left">
                <SheetTitle>{{ props.title }}</SheetTitle>
                <SheetDescription>{{ props.description }}</SheetDescription>
                <Separator class="mt-2" />
            </SheetHeader>

            <ScrollArea class="flex-1 overflow-y-auto">
                <form @submit.prevent="showConfirm = true" class="flex-1 space-y-4 px-4" ref="userForm">
                    <Label for="name">{{ trans('label.name') }}</Label>
                    <Input type="text" id="name" v-model="form.name" :aria-invalid="!!form.errors.name" @input="form.clearErrors('name')" required autofocus :tabindex="1" autocomplete="name" :placeholder="trans('placeholder.enter_name')" />
                    <InputError :message="form.errors.name" />

                    <Label for="email">{{ trans('label.email_address') }}</Label>
                    <Input type="email" id="email" v-model="form.email" :aria-invalid="!!form.errors.email" @input="form.clearErrors('email')" required autocomplete="email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />

                    <Label for="status">
                        <Checkbox id="status" :model-value="form.is_active" @update:model-value="(toggleStatus as any)" />
                        <span class="font-semibold">{{ trans('label.active') }}</span>
                    </Label>
                    <InputError :message="form.errors.is_active" />
                </form>
            </ScrollArea>

            <SheetFooter class="flex items-center">
                <Separator class="mt-2" />
                <div class="flex justify-stretch items-center px-4 py-2 gap-2">
                    <SheetClose asChild>
                        <Button variant="outline" :disabled="form.processing">
                            <XCircleIcon />
                            {{ trans('button.close') }}
                        </Button>
                    </SheetClose>
                    <Button variant="default" form="tasks-form" type="button" @click="handleSubmit" :disabled="form.processing">
                        <SaveIcon />
                        {{ trans('button.save_changes') }}
                    </Button>
                </div>
            </SheetFooter>
        </SheetContent>
    </Sheet>

    <ConfirmDialog type="submit" :open="showConfirm" :onConfirm="submitConfirmed" :onCancel="handleCancel" :loading="form.processing" :title="trans('label.confirm_save')" :description="trans('label.are_you_sure_to_save_this_data')"></ConfirmDialog>
</template>
