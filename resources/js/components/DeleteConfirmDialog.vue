<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogFooter,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogCancel,
    AlertDialogAction,
} from '@/components/ui/alert-dialog'

interface Props {
    onConfirm: (params: any) => void;
    onCancel: () => void;
    open: boolean;
    loading?: boolean;
    title?: string;
    description?: string;
    detail?: string;
}

defineProps<Props>();

</script>

<template>
    <AlertDialog :open="open" @update:open="onCancel">
        <AlertDialogTrigger as-child>
            <slot name="trigger" />
        </AlertDialogTrigger>

        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title ?? 'Are you sure?' }}</AlertDialogTitle>
                <AlertDialogDescription>
                    <div v-html="description ?? 'This action cannot be undone.'" class="mb-4"></div>
                    <b>
                        <div v-html="detail"></div>
                    </b>
                </AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel :disabled="loading" @click="onCancel">
                    Cancel
                </AlertDialogCancel>
                <AlertDialogAction :disabled="loading" @click="onConfirm">
                    <span v-if="!loading">Confirm</span>
                    <span v-else>Deleting...</span>
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
