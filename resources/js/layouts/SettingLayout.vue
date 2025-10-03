<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link, usePage } from '@inertiajs/vue3';
import { Separator } from '@/components/ui/separator';
import { settingNavItems } from '@/datasource/sidebardata';
import Heading from '@/components/Heading.vue';
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue';
import ScrollBar from '@/components/ui/scroll-area/ScrollBar.vue';
import useCan from '@/composables/useCan';

interface ZiggyProps {
    ziggy?: {
        location?: string;
        [key: string]: any;
    };
    [key: string]: unknown;
}

const { canAny } = useCan();
const page = usePage<ZiggyProps>();
const location = page.props.ziggy?.location ?? null;
const currentPath = location ? new URL(location).pathname : '';

</script>

<template>
    <div class="px-4 py-6">
        <Heading :title="trans('label.settings')" :description="trans('label.manage_your_profile_and_account_settings')" />

        <div class="flex flex-col md:space-y-0 lg:flex-row lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <ScrollArea type='auto' orientation="horizontal" className='bg-background w-full min-w-40 px-1 py-2 md:block'>
                    <nav class="flex lg:flex-col space-x-0 space-y-1">
                        <template v-for="item in settingNavItems" :key="item.href">
                            <Button v-if="canAny(item.route!!)" variant="ghost" :class="['justify-start', { 'bg-muted': currentPath === item.href || item.isActive }]" as-child>
                                <Link :href="item.href">
                                {{ trans(item.title) }}
                                </Link>
                            </Button>
                        </template>
                    </nav>
                    <ScrollBar type='always' orientation="horizontal" />
                </ScrollArea>
            </aside>

            <Separator class="my-2 lg:hidden" />

            <div class="flex space-y-12 w-full overflow-y-hidden sticky h-screen px-1">
                <slot />
            </div>
        </div>
    </div>
</template>
