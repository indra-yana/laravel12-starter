<script setup lang="ts">
import {
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogHeader,
    DialogFooter,
    DialogTitle,
    DialogDescription,
    DialogClose,
    DialogOverlay,
} from '@/components/ui/dialog'
import { Button } from './ui/button'
import { computed } from 'vue'
import { Trash2, Pencil, CheckCircle, HelpCircle } from 'lucide-vue-next'

interface Props {
    onConfirm: (params?: any) => void;
    onCancel: () => void;
    open: boolean;
    loading?: boolean;
    title?: string;
    description?: string;
    detail?: string;
    type?: 'delete' | 'edit' | 'submit' | 'custom';
}

const props = defineProps<Props>()

const iconComponent = computed(() => {
    switch (props.type) {
        case 'delete':
            return Trash2
        case 'edit':
            return Pencil
        case 'submit':
            return CheckCircle
        default:
            return HelpCircle
    }
})

const confirmText = computed(() => {
    if (props.loading) {
        return props.type === 'delete' ? 'Deleting...' : 'Processing...'
    }

    switch (props.type) {
        case 'delete':
            return 'Delete'
        case 'edit':
            return 'Save Changes'
        case 'submit':
            return 'Submit'
        default:
            return 'Confirm'
    }
})
</script>

<template>
    <Dialog :open="open" @update:open="onCancel" :modal="true">
        <DialogOverlay class="bg-backdrop-blur-sm z-[10]" />

        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>

        <DialogContent>
            <DialogHeader>
                <div class="flex items-center gap-2">
                    <component :is="iconComponent" class="size-5" :class="type === 'delete' ? 'text-destructive' : 'text-primary'" />
                    <DialogTitle>
                        {{ title ?? 'Are you sure?' }}
                    </DialogTitle>
                </div>
            </DialogHeader>

            <DialogDescription>
                <div v-html="description ?? 'This action cannot be undone.'" class="mb-3" />
                <div v-if="detail" class="text-sm font-semibold" v-html="detail" />
            </DialogDescription>

            <DialogFooter class="flex justify-end gap-2">
                <DialogClose as-child>
                    <Button variant="outline" :disabled="loading" @click="onCancel">
                        Cancel
                    </Button>
                </DialogClose>
                <Button :variant="type === 'delete' ? 'destructive' : 'default'" :disabled="loading" @click="onConfirm">
                    {{ confirmText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
