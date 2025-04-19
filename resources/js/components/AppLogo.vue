<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage<SharedData>();

const appName = page.props.name;
const appVersion = page.props.version;

</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <div class="flex flex-col gap-0.5 leading-none">
            <span class="truncate font-semibold leading-none">{{ appName }}</span>
            <span class="">{{ appVersion }}</span>
        </div>
    </div>
</template>
