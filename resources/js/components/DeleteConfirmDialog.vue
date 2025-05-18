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


defineProps<{
    open: boolean
    onConfirm: (params: any) => void
    onCancel: () => void
    loading?: boolean
    title?: string
    description?: string
}>()
</script>

<template>
    <AlertDialog :open="open" @update:open="onCancel">
        <AlertDialogTrigger as-child>
            <!-- Optional trigger slot -->
            <slot name="trigger" />
        </AlertDialogTrigger>

        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title ?? 'Are you sure?' }}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{ description ?? 'This action cannot be undone.' }}
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
