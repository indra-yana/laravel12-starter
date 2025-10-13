<script setup lang="ts">
import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter, DialogDescription, } from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { MonthlyPeriodProps } from "./LkbCard.vue";
import { monthNames, units } from "@/lib/utils";
import { ref, watch } from "vue";
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
import { Trash2, Plus } from "lucide-vue-next";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";

export interface MonthlyWorkProps {
    id?: number | null;
    monthly_period_id: number;
    title: string;
    target: number | string;
    unit: string;
}

interface MonthlyWorkForm {
    period_id: number | null;
    targets: MonthlyWorkProps[];
    [key: string]: any;
}

const props = defineProps<{
    show: boolean;
    monthlyPeriod: MonthlyPeriodProps | null;
    monthlyPeriodId: number | null;
    existingTargets?: MonthlyWorkProps[] | null;
}>()

const emit = defineEmits<{
    (e: "close"): void
    (e: "saved"): void
}>()

const targetsForm = ref();
const rows = ref<MonthlyWorkProps[]>([])
const form = useForm<MonthlyWorkForm>({
    period_id: props.monthlyPeriodId,
    targets: [] as MonthlyWorkProps[],
})

const monthlyWorkDeleteForm = useForm<Omit<MonthlyWorkForm, 'id'>>({
    id: null,
})

watch(() => props.monthlyPeriodId, (newVal, oldVal) => {
    form.clearErrors();
    form.reset()
    form.period_id = props.monthlyPeriodId;

    if (newVal) {
        if (props.existingTargets && props.existingTargets.length) {
            rows.value = props.existingTargets.map((t) => ({
                ...t,
                monthly_period_id: props.monthlyPeriodId!,
            }))
        } else {
            rows.value = [
                {
                    monthly_period_id: props.monthlyPeriodId!,
                    title: "",
                    target: 1,
                    unit: "Kegiatan",
                },
            ]
        }
    }
})

function addRow() {
    rows.value.push({
        monthly_period_id: props.monthlyPeriodId!,
        title: "",
        target: 1,
        unit: "Kegiatan",
    })
}

function removeRow(index: number, rowId?: number | null) {
    if (rowId == null) {
        rows.value.splice(index, 1);
    } else {
        monthlyWorkDeleteForm.id = rowId;
        monthlyWorkDeleteForm.delete(route('logbook.lkb.monthlyworks.delete'), {
            preserveScroll: true,
            preserveState: true,
            onError: () => { },
            onSuccess: () => {
                rows.value.splice(index, 1);
            },
        });
    }
}

function handleSubmit() {
    if (targetsForm.value && targetsForm.value.reportValidity()) {
        form.targets = rows.value
        form.post(route("logbook.lkb.monthlyworks.store"), {
            preserveScroll: true,
            preserveState: ({ props }) => !!Object.keys(props.errors).length,
            onSuccess: () => {
                emit("saved")
                handleClose()
            },
        })
    }
}

function handleClose() {
    emit("close")
}

</script>

<template>
    <Dialog :open="show" @update:open="handleClose">
        <DialogContent class="!max-w-3xl w-full grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh]">
            <DialogHeader class="space-y-3">
                <DialogTitle class="mb-1">Tambah / Kelola Sasaran </DialogTitle>
                <DialogDescription>
                    LKB: {{ `${monthNames[(monthlyPeriod?.month || 1) - 1]} ${monthlyPeriod?.year}` }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" ref="targetsForm" class=" overflow-y-auto">
                <div class="space-y-2 my-4 ">
                    <div v-for="(row, index) in rows" :key="index" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 p-1.5 items-start ">
                        <div class="sm:col-span-6 gap-1">
                            <Label :for="`title-${index}`" class="mb-2.5">{{ trans('Nama Kegiatan') }} {{ index + 1 }}<span class="text-destructive">*</span></Label>
                            <Input type="text" :id="`title-${index}`" v-model="row.title" :aria-invalid="!!form.errors[`targets.${index}.title`]" @input="form.clearErrors(`targets.${index}.title`)" required :placeholder="trans('Nama kegiatan')" />
                            <InputError :message="form.errors[`targets.${index}.title`]" />
                        </div>

                        <div class="sm:col-span-2">
                            <Label :for="`target-${index}`" class="mb-2.5">{{ trans('Jumlah') }} <span class="text-destructive">*</span></Label>
                            <Input type="number" min="0" max="100" default-value="1" :id="`target-${index}`" v-model="row.target" :aria-invalid="!!form.errors[`targets.${index}.target`]" @input="form.clearErrors(`targets.${index}.target`)" required placeholder="0" />
                            <InputError :message="form.errors[`targets.${index}.target`]" />
                        </div>

                        <div class="sm:col-span-2">
                            <Label :for="`unit-${index}`" class="mb-2.5">{{ trans('Satuan') }} <span class="text-destructive">*</span></Label>
                            <Select v-model="row.unit" default-value="Kegiatan" :aria-invalid="!!form.errors[`targets.${index}.unit`]" @update:modelValue="form.clearErrors(`targets.${index}.unit`)">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih satuan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="(unit, uIndex) in units" :key="uIndex" :value="unit">
                                        {{ unit }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors[`targets.${index}.unit`]" />
                        </div>

                        <div class="sm:col-span-2">
                            <div class="w-full flex justify-end gap-1 mt-6">
                                <Button v-if="rows.length > 1" type="button" variant="destructive" size="icon" :disabled="monthlyWorkDeleteForm.processing" :title="trans('Hapus Baris')" @click="removeRow(index, row.id)">
                                    <Trash2 class="size-5" />
                                </Button>
                                <Button v-if="index === rows.length - 1" type="button" variant="outline" :title="trans('Tambah Baris')" size="icon" :disabled="monthlyWorkDeleteForm.processing" @click="addRow">
                                    <Plus class="size-5" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <DialogFooter class="mt-6 flex justify-end gap-2">
                <Button type="button" variant="outline" @click="handleClose">
                    {{ trans('button.cancel') }}
                </Button>
                <Button type="button" :disabled="form.processing" @click="handleSubmit">
                    {{ form.processing ? trans('button.saving') : trans('button.save') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
