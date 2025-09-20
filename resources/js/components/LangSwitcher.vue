<script setup lang="ts">
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue, } from "@/components/ui/select";
import { SharedData } from "@/types";

type Language = {
    label: string
    value: string
    flag: string
}

const languages: Language[] = [
    { label: "English", value: "en", flag: "/assets/images/flags/en.svg" },
    { label: "Bahasa", value: "id", flag: "/assets/images/flags/id.svg" },
]

const page = usePage<SharedData>();
const locale = page.props.app.locale;
const fallbackLocale = page.props.app.fallback_locale;
const selected = ref<string>(locale || fallbackLocale);

function onLanguageSelected(locale: any): any {
    router.visit(route("appearance.locale", { locale }), {
        preserveScroll: true,
        preserveState: false,
    })
}

</script>

<template>
    <Select v-model="selected" @update:modelValue="onLanguageSelected">
        <SelectTrigger class="w-[220px] text-left">
            <span class="flex gap-2">
                <img v-if="selected" :src="languages.find(l => l.value === selected)?.flag" alt="" class="size-5 shadow-sm object-cover rounded-full" />
                <SelectValue placeholder="Select language..." class=" text-left" />
            </span>
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem v-for="lang in languages" :key="lang.value" :value="lang.value">
                    <span class="flex items-center gap-2">
                        <img :src="lang.flag" alt="" class="size-5 mr-2 shadow-sm object-cover rounded-full" />
                        <span>{{ lang.label }}</span>
                    </span>
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
