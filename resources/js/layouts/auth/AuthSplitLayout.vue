<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;
const version = page.props.version;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="lg:p-8">
            <div class="flex w-full flex-col justify-center mx-auto space-y-6 sm:w-[350px]">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-sm text-muted-foreground" v-if="description">{{ description }}</p>
                </div>
                <slot />
            </div>
        </div>
        <div class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex">
            <div class="absolute inset-0 bg-zinc-900" />
            <Link :href="route('home')" class="relative z-20 flex items-center text-lg font-medium">
            <AppLogoIcon class="mr-2 size-8 fill-current text-white" />
            <div class="flex flex-col gap-0.5 leading-none">
                <span class="truncate font-semibold leading-none">{{ name }}</span>
                <span class="text-sm">{{ version }}</span>
            </div>
            </Link>
            <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-neutral-300">{{ quote.author }}</footer>
                </blockquote>
                <div class="flex flex-col gap-2 leading-none mt-6">
                    <span class="text-sm font-medium tracking-tight">By awesome stack</span>
                    <img src="/assets/images/fw-stack.png" alt="" width="250px">
                </div>
            </div>
        </div>
    </div>
</template>
