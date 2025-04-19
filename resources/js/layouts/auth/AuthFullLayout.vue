<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';
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

    <div id="auth-wrapper" class="relative grid h-dvh flex-col items-center justify-center px-4 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="lg:ps-20">
            <div class="flex w-full flex-col justify-start space-y-6 sm:w-[400px] p-2 sm:p-4 lg:p-8 bg-backdrop-blur-sm rounded-2xl">
                <div class="flex flex-col space-y-2 text-center ">
                    <div class="flex flex-col items-center gap-2">
                        <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="flex h-16 w-16 items-center justify-center rounded-md">
                            <AppLogoIcon class="mr-2 fill-current text-dark dark:text-white" />
                        </div>
                        <span class="sr-only">MyCompany Inc.</span>
                        <h1 class="text-xl font-bold dark:text-white">
                            MyCompany Inc.
                        </h1>
                        </Link>
                        <div class="text-center text-xl text-muted-foreground">
                            <span class="tracking-tight">{{ name }}</span>
                            <p class="text-sm" v-if="description">{{ description }}</p>
                        </div>
                    </div>
                </div>
                <slot />
            </div>
            <div class="hidden md:flex flex-col gap-2 leading-none mt-8">
                <span class="text-sm font-medium dark:text-muted-foreground">By awesome stack</span>
                <!-- <img src="/assets/images/fw-stack.png" alt="" width="250px"> -->
                <div class="flex flex-wrap items-start gap-4 md:gap-8 text-center">
                    <div class="size-10 lg:size-14">
                        <img src="/assets/images/logo/laravel.png" alt="Laravel Logo Placeholder" class="rounded-lg mx-auto mb-2 size-10 lg:size-12">
                        <p class="text-sm dark:text-muted-foreground">Laravel</p>
                    </div>
                    <div class="size-10 lg:size-14">
                        <img src="/assets/images/logo/inertia.png" alt="Inertia.js Logo Placeholder" class="rounded-lg mx-auto mb-2 size-10 lg:size-12">
                        <p class="text-sm dark:text-muted-foreground">Inertia.js</p>
                    </div>
                    <div class="size-10 lg:size-14">
                        <img src="/assets/images/logo/vue.png" alt="Vue.js Logo Placeholder" class="rounded-lg mx-auto mb-2 size-10 lg:size-12">
                        <p class="text-sm dark:text-muted-foreground">Vue.js</p>
                    </div>
                    <div class="size-10 lg:size-14">
                        <img src="/assets/images/logo/tailwind.png" alt="Tailwind CSS Logo Placeholder" class="rounded-lg mx-auto mb-2 size-10 lg:size-12 object-contain">
                        <p class="text-sm dark:text-muted-foreground">Tailwind</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
