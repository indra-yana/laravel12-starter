<script setup lang="ts">
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import SiteSelect from '@/components/SiteSelect.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';

const page = usePage<SharedData>();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <!-- <div class="video-container z-10">
        <video autoplay muted loop no-controls class="hidden md:flex" >
            <source src="/assets/images/login-v2.webm" type="video/mp4" />
        </video>
        <video autoplay muted loop no-controls class="flex md:hidden" >
            <source src="/assets/images/login-v2-mobile.webm" type="video/mp4" />
        </video>
    </div> -->

    <div class="relative z-2">
        <div class="absolute bottom-0 left-0 right-0 top-0 grid place-items-center py-8">
            <div class="flex gap-2.5">
                <SiteSelect class="w-64" />
                <ThemeSwitcher class="flex-auto" />
            </div>
        </div>
    </div>

    <div class="relative grid h-dvh flex-col items-center justify-center px-4 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 ">
        <div class="lg:p-21">
            <div class="flex w-full flex-col justify-start space-y-6 sm:w-[400px] p-2 sm:p-4 lg:p-8 bg-slate-400/10 dark:bg-slate-700/20 backdrop-blur-sm rounded-2xl">
                <div class="flex flex-col space-y-2 text-center ">
                    <div class="flex flex-col items-center gap-2">
                        <a :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                            <div class="flex h-16 w-16 items-center justify-center rounded-md">
                                <AppLogoIcon class="mr-2 fill-current text-dark dark:text-white" />
                            </div>
                            <span class="sr-only">MyCompany Inc.</span>
                            <h1 class="text-xl font-bold">
                                MyCompany Inc.
                            </h1>
                        </a>
                        <div class="text-center text-xl text-muted-foreground">
                            <span class="tracking-tight">{{ name }}</span>
                            <p class="text-sm" v-if="description">{{ description }}</p>
                        </div>
                    </div>
                </div>
                <slot />
            </div>
            <div class="flex flex-col gap-2 leading-none mt-8">
                <span class="text-sm font-medium tracking-tight">By awesome stack</span>
                <img src="/assets/images/fw-stack.png" alt="" width="250px">
            </div>
        </div>
    </div>
</template>
