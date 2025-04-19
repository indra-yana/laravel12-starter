<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useForm } from '@inertiajs/vue3';

const appurl = import.meta.env.VITE_APP_URL || 'http://localhost';
const form = useForm({
    site: `${appurl}`,
});

function handleChange(value: any): void {
    if (value == appurl) {
        return;
    }

    window.location.href = value
}

</script>

<template>
    <Select v-model="form.site" @update:model-value="handleChange">
        <SelectTrigger class="w-[230px] rounded-full shadow" >
            <SelectValue placeholder="Visit other site" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Visit other site</SelectLabel>
                <SelectItem :value="appurl">
                    Site A
                </SelectItem>
                <SelectItem value="#">
                    Site B
                </SelectItem>
                <SelectItem value="##">
                    Site C
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>