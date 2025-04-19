<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="lg:p-16">
            <div class="flex w-full flex-col justify-start space-y-6 sm:w-[350px]">
                <div class="flex flex-col space-y-2 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <a :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                            <div class="flex h-16 w-16 items-center justify-center rounded-md">
                                <AppLogoIcon class="mr-2 fill-current text-white" />
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
            <div class="flex flex-col gap-2 leading-none mt-18">
                <span class="text-sm font-medium tracking-tight">By awesome stack</span>
                <img src="/assets/images/fw-stack.png" alt="" width="250px">
            </div>
        </div>
    </div>
</template>
