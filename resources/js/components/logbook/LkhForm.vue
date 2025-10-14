<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DateSelectArg, EventInput } from '@fullcalendar/core/index.js';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MonthlyWorkProps } from './AddTargetsModal.vue';
import { ref, watch, computed } from 'vue';
import { SaveIcon, Trash2, XCircleIcon } from 'lucide-vue-next';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue, } from "@/components/ui/select";
import { Separator } from '@/components/ui/separator';
import { SharedData } from '@/types';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle } from '@/components/ui/sheet';
import { units } from '@/lib/utils';
import { useForm, usePage } from '@inertiajs/vue3';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import InputError from '@/components/InputError.vue';

export interface DailyWorkField extends Omit<EventInput, 'title' | 'start'> {
    id?: string | number | null;
    monthly_work_id?: number | null;
    title: string;
    quantity: number;
    unit: string;
    output: string;
    start: string;
    end: string;
    evidence_link: string;
    file: object | null;
    [key: string]: any;
}

interface Props {
    open: boolean;
    title: string;
    description?: string;
    selectedDate?: DateSelectArg;
    currentRow?: DailyWorkField | null;
    onOpenChange: (open: boolean) => void;
}

const logbookForm = ref();
const page = usePage<SharedData>();
const props = defineProps<Props>();
const isUpdate = computed(() => !!props.currentRow?.id);
const showConfirm = ref(false);
const showDeleteConfirm = ref(false);
// TODO: Dynamic update based on selected month and years 
const monthlyWorks = ref<MonthlyWorkProps[]>(page.props.monthly_works as MonthlyWorkProps[]);
const form = useForm<DailyWorkField>({
    id: null,
    monthly_work_id: null,
    title: '',
    output: '',
    quantity: 1,
    unit: 'Kegiatan',
    evidence_link: '',
    start: '',
    end: '',
    file: {},
    uploaded_file: null as File | null,
});


const deleteform = useForm<Omit<DailyWorkField, 'id'>>({
    id: null,
});

watch(() => props.open, (open) => {
    if (!open) resetForm();
    if (isUpdate) populateForm();
});

function populateForm() {
    form.id = props.currentRow?.id || null;
    form.monthly_work_id = props.currentRow?.monthly_work_id ?? null;
    form.title = props.currentRow?.title || '';
    form.quantity = props.currentRow?.quantity || 1;
    form.unit = props.currentRow?.unit || 'Kegiatan';
    form.output = props.currentRow?.output || '';
    form.start = props.currentRow?.start ?? (props.selectedDate?.startStr || '');
    form.end = form.start;
    // form.end = props.currentRow?.end ?? (props.selectedDate?.endStr || props.selectedDate?.startStr || '');
    form.evidence_link = props.currentRow?.evidence_link ?? '';
}

function handleSubmit() {
    if (logbookForm.value && logbookForm.value.reportValidity()) {
        showConfirm.value = true
    }
}

function handleDelete() {
    deleteform.id = props.currentRow?.id || null;
    showDeleteConfirm.value = true;
}

function deleteConfirmed() {
    deleteform.delete(route('logbook.lkh.delete'), {
        preserveScroll: true,
        preserveState: ({ props }) => !!Object.keys(props.errors).length,
        onError: () => handleDeleteCancel(),
        onSuccess: () => {
            props.onOpenChange(false);
            handleDeleteCancel();
        },
    });
}

function handleDeleteCancel() {
    showDeleteConfirm.value = false;
}

function submitConfirmed() {
    const url = isUpdate.value ? route('logbook.lkh.update') : route('logbook.lkh.store');
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

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.uploaded_file = file;
    form.clearErrors('uploaded_file');
}

function handleCancel() {
    showConfirm.value = false
}

function resetForm() {
    form.reset();
    form.clearErrors();
}

function handleLkbUSelect(id: any) {
    const selectedLkb = monthlyWorks.value.filter((e) => e.id == id)[0];
    form.unit = selectedLkb.unit;
}

</script>

<template>
    <Sheet :open="props.open" @update:open="props.onOpenChange">
        <SheetContent class="flex flex-col h-full w-full">
            <SheetHeader class="text-left">
                <SheetTitle>{{ props.title }}</SheetTitle>
                <SheetDescription>{{ props.description }}</SheetDescription>
                <Separator class="mt-2" />
            </SheetHeader>

            <ScrollArea class="flex-1 overflow-y-auto">
                <form @submit.prevent="showConfirm = true" class="flex-1 space-y-4 px-4" ref="logbookForm">

                    <Label for="unit" class="mb-3">Sasaran Bulanan (LKB)</Label>
                    <Select v-model="form.monthly_work_id" @update:model-value="handleLkbUSelect">
                        <SelectTrigger id="unit" :aria-invalid="!!form.errors.unit" class="w-full">
                            <SelectValue :placeholder="trans('Pilih sasaran')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="work in monthlyWorks" :key="work.id!!" :value="work.id!!">
                                    {{ work.title }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <Label for="title">{{ trans('Nama Kegiatan') }} <span class="text-destructive">*</span></Label>
                    <Input type="text" id="title" v-model="form.title" :aria-invalid="!!form.errors.title" @input="form.clearErrors('title')" required autofocus :tabindex="1" :placeholder="trans('Nama kegiatan harian')" />
                    <InputError :message="form.errors.title" />

                    <Label for="output">{{ trans('Output') }} <span class="text-destructive">*</span></Label>
                    <Input type="text" id="output" v-model="form.output" :aria-invalid="!!form.errors.output" @input="form.clearErrors('output')" required autofocus :tabindex="1" autocomplete="output" :placeholder="trans('Output kegiatan')" />
                    <InputError :message="form.errors.output" />

                    <div class="flex gap-2">
                        <div class="w-14">
                            <Label for="quantity" class="mb-3">{{ trans('Kuantitas') }} <span class="text-destructive">*</span></Label>
                            <Input type="number" min="1" max="100" id="quantity" v-model="form.quantity" :aria-invalid="!!form.errors.quantity" @input="form.clearErrors('quantity')" required autofocus :tabindex="1" autocomplete="quantity" :placeholder="trans('Banyaknya kegiatan')" />
                        </div>
                        <div class="w-64 flex-1">
                            <Label for="unit" class="mb-3">&nbsp;</Label>
                            <Select v-model="form.unit">
                                <SelectTrigger id="unit" :aria-invalid="!!form.errors.unit" class="w-full">
                                    <SelectValue :placeholder="trans('Satuan')" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="unit in units" :key="unit" :value="unit">
                                            {{ unit }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <InputError :message="form.errors.quantity" />

                    <Label for="evidence_link">{{ trans('Link Data Dukung') }}</Label>
                    <Input type="text" id="evidence_link" v-model="form.evidence_link" :aria-invalid="!!form.errors.evidence_link" @input="form.clearErrors('evidence_link')" :placeholder="trans('Link google drive')" />
                    <InputError :message="form.errors.evidence_link" />

                    <!-- TODO: handle upload file -->
                    <!-- <Label for="uploaded_file">{{ trans('Data Dukung (Opsional)') }}</Label>
                    <Input type="file" id="uploaded_file" @change="handleFileChange" :aria-invalid="!!form.errors.uploaded_file" :placeholder="trans('Data pendukung')" />
                    <InputError :message="form.errors.uploaded_file" /> -->

                    <!-- <Label for="status">
                        <Checkbox id="status" :model-value="form.is_active" @update:model-value="(toggleStatus as any)" />
                        <span class="font-semibold">{{ trans('label.active') }}</span>
                    </Label>
                    <InputError :message="form.errors.is_active" /> -->
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
                        <template v-if="isUpdate">
                            {{ trans('button.save_changes') }}
                        </template>
                        <template v-else>
                            {{ trans('button.save') }}
                        </template>
                    </Button>
                    <template v-if="isUpdate">
                        <Separator orientation="vertical" />
                        <Button variant="destructive" form="tasks-form" type="button" @click="handleDelete" :disabled="form.processing">
                            <Trash2 />
                        </Button>
                    </template>
                </div>
            </SheetFooter>
        </SheetContent>
    </Sheet>

    <ConfirmDialog id="submit-confirm" type="submit" :open="showConfirm" :onConfirm="submitConfirmed" :onCancel="handleCancel" :loading="form.processing" :title="trans('label.confirm_save')" :description="trans('label.are_you_sure_to_save_this_data')"></ConfirmDialog>
    <ConfirmDialog id="delete-confirm" v-if="isUpdate" type="delete" :open="showDeleteConfirm" :onConfirm="deleteConfirmed" :onCancel="handleDeleteCancel" :loading="deleteform.processing" :title="trans('label.delete_confirm')" :description="trans('label.delete_data_description')"></ConfirmDialog>
</template>
