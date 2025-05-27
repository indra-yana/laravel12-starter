<script setup lang="ts">
import { AlertDialog, AlertDialogTrigger, AlertDialogContent, AlertDialogHeader, AlertDialogFooter, AlertDialogTitle, AlertDialogDescription, AlertDialogCancel, } from '@/components/ui/alert-dialog';
import { Button } from './ui/button';
import { computed } from 'vue';
import { Trash2, Pencil, CheckCircle, HelpCircle } from 'lucide-vue-next';

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
    <AlertDialog :open="open" @update:open="onCancel">
        <AlertDialogTrigger as-child>
            <slot name="trigger" />
        </AlertDialogTrigger>

        <AlertDialogContent>
            <AlertDialogHeader>
                <div class="flex items-center gap-2">
                    <component :is="iconComponent" class="size-5" :class="type === 'delete' ? 'text-destructive' : 'text-primary'" />
                    <AlertDialogTitle>
                        {{ title ?? 'Are you sure?' }}
                    </AlertDialogTitle>
                </div>
            </AlertDialogHeader>

            <AlertDialogDescription>
                <div v-html="description ?? 'This action cannot be undone.'" class="mb-3"></div>
                <div v-if="detail" class="text-sm font-semibold" v-html="detail"></div>
            </AlertDialogDescription>

            <AlertDialogFooter>
                <AlertDialogCancel :disabled="loading" @click="onCancel">
                    Cancel
                </AlertDialogCancel>
                <Button :variant="type === 'delete' ? 'destructive' : 'default'" :disabled="loading" @click="onConfirm">
                    {{ confirmText }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
