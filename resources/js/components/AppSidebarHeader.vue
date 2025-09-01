<script setup lang="ts">
import { onMounted, reactive } from 'vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import ThemeSwitcher from './ThemeSwitcher.vue';
import type { BreadcrumbItemType } from '@/types';

defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>();

const data = reactive({
	fixed: false,
});

onMounted(() => {
	window.addEventListener("scroll", () => {
		let scrollPos = window.scrollY;
        data.fixed = scrollPos > 0;
	});
})

</script>

<template>
    <header class="sticky top-0 z-10 bg-background rounded-t-xl flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4" :class="{'bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border-slate-300/50 dark:border-slate-700/50 transition-colors duration-500': data.fixed}">
        <div class="flex justify-between items-center gap-2 w-full">
            <div class="flex items-center">
                <SidebarTrigger class="-ml-1" />
                <template v-if="(breadcrumbs?.length || 0) > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template>
            </div>
            <div>
                <ThemeSwitcher class="flex-auto" />
            </div>
        </div>
    </header>
</template>
