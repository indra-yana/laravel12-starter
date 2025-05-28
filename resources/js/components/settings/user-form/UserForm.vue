<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ConfirmDialog from '@/components/ConfirmDialog.vue';


const props = defineProps<{
    open: boolean
    onOpenChange: (open: boolean) => void
    title: string
    description?: string
    currentRow?: {
        id?: number
        name: string
        email: string
        is_active: boolean
    }
}>()

const isUpdate = computed(() => !!props.currentRow?.id)

const form = useForm({
    name: props.currentRow?.name || '',
    email: props.currentRow?.email || '',
    is_active: props.currentRow?.is_active ?? true,
})

const showConfirm = ref(false)

watch(() => props.open, (v) => {
    if (!v) form.reset()
})

function submitConfirmed() {
    const url = isUpdate.value ? route('users.update') : route('users.store');
    const method = isUpdate.value ? form.put : form.post;

    method(url, {
        onSuccess: () => {
            props.onOpenChange(false);
            form.reset();
            handleCancel();
        },
    })
}

function handleCancel() {
    showConfirm.value = false
}

</script>

<template>
    <Sheet :open="props.open" @update:open="props.onOpenChange">
        <SheetContent class="flex flex-col">
            <SheetHeader class="text-left">
                <SheetTitle>{{ props.title }}</SheetTitle>
                <SheetDescription>{{ props.description }}</SheetDescription>
            </SheetHeader>

            <form @submit.prevent="showConfirm = true" class="flex-1 space-y-5 px-4">
                <FormItem label="Name" :error="form.errors.name">
                    <Input v-model="form.name" placeholder="Enter name" />
                </FormItem>

                <FormItem label="Email" :error="form.errors.email">
                    <Input v-model="form.email" placeholder="Enter email" type="email" />
                </FormItem>

                <FormItem>
                    <label class="flex items-center space-x-2">
                        <Checkbox v-model:checked="form.is_active" />
                        <span>Status</span>
                    </label>
                </FormItem>
            </form>

            <SheetFooter class="gap-2">
                <SheetClose asChild>
                    <Button variant="outline">Close</Button>
                </SheetClose>
                <Button form="tasks-form" type="submit">Save</Button>
            </SheetFooter>
        </SheetContent>
    </Sheet>

    <ConfirmDialog type="delete" :open="showConfirm" :onConfirm="submitConfirmed" :onCancel="handleCancel" :loading="form.processing" title="Confirm Save" description="Are you sure you want to save this data?"></ConfirmDialog>
</template>
