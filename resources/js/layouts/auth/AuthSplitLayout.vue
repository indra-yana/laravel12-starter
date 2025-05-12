<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';

const page = usePage<SharedData>();
const name = page.props.name;
const version = page.props.version;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="video-container z-10">
        <img src="/assets/images/bg-dark.png" alt="Dark mode background" class="w-full h-full hidden dark:block">
        <img src="/assets/images/bg-light.png" alt="Light mode background" class="w-full h-full block dark:hidden">
    </div>
    <div class="relative grid h-dvh flex-col items-center justify-center px-4 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="justify-center items-center">
            <div class="flex w-full flex-col justify-center mx-auto space-y-6 sm:w-[400px] p-2 sm:p-4 lg:p-6 bg-backdrop-blur-sm rounded-2xl">
                <div class="flex flex-col space-y-2  ">
                    <div class="flex flex-col  gap-2">
                        <div class="flex justify-between">
                            <Link :href="route('home')" class="relative z-20 flex items-center text-lg font-medium mb-6">
                            <AppLogoIcon class="mr-2 size-8 fill-current text-dark dark:text-white rounded-md" />
                            <div class="flex flex-col gap-0.5 leading-none">
                                <span class="truncate font-semibold leading-none">{{ name }}</span>
                                <span class="text-sm">{{ version }}</span>
                            </div>
                            </Link>
                            <ThemeSwitcher class="flex-auto" />
                        </div>
                        <div class="text-center text-xl text-muted-foreground">
                            <p class="text-sm" v-if="description">{{ description }}</p>
                        </div>
                    </div>
                </div>
                <slot />
            </div>
        </div>
        <div class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex">
            <div class="absolute inset-0 bg-zinc-900" />
            <div v-if="quote" class="relative z-20 flex items-center text-lg font-medium">
                <blockquote class="space-y-2">
                    <p class="text-lg">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-neutral-300">— {{ quote.author }}</footer>
                </blockquote>
            </div>
            <div class="relative z-20 mt-auto">
                <div class="flex flex-col gap-2 leading-none mt-6">
                    <span class="text-sm font-medium text-muted dark:text-muted-foreground">By awesome stack</span>
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
    </div>
</template>
