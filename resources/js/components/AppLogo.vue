<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const appVersion = import.meta.env.VITE_APP_VERSION || 'v1.0.0';

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
