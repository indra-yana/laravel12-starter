<script setup lang="ts">
import { activeInclude, checkIsActive } from "@/lib/utils";
import { ChevronRight } from "lucide-vue-next";
import { defineProps } from "vue";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuItem } from "@/components/ui/dropdown-menu";
import { Link } from "@inertiajs/vue3";
import { NavItem } from "@/types";
import { SidebarMenuButton, SidebarMenuItem } from "@/components/ui/sidebar";
import Icon from "@/components/Icon.vue";
import NavBadge from "./NavBadge.vue";
import useCan from "@/composables/useCan";

interface Props {
    item: NavItem;
    href: string;
}

defineProps<Props>();
const { canAny } = useCan();

</script>

<template>
    <SidebarMenuItem>
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <SidebarMenuButton :tooltip="item.title" :is-active="checkIsActive(href, item)">
                    <!-- <component :is="item.icon" v-if="item.icon" /> -->
                    <Icon :name="(item.icon as string)" v-if="item.icon" />
                    <span>{{ trans(item.title) }}</span>
                    <NavBadge v-if="item.badge">{{ item.badge }}</NavBadge>
                    <ChevronRight class="ms-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                </SidebarMenuButton>
            </DropdownMenuTrigger>

            <DropdownMenuContent side="right" align="start" :side-offset="4" class="p-2">
                <DropdownMenuLabel>
                    {{ trans(item.title) }} <span v-if="item.badge">({{ item.badge }})</span>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem v-for="sub in item.items" :key="`${sub.title}-${sub.href}`" :data-state="checkIsActive(href, sub) || activeInclude(sub) ? 'on' : 'off'" class="my-1.5 cursor-pointer">
                    <Link v-if="canAny(sub.route!!)" :href="sub.href" class="flex items-center gap-2 w-full">
                    <component :is="sub.icon" v-if="sub.icon" class="w-4 h-4 " />
                    <span class="w-52 text-wrap">{{ trans(sub.title) }}</span>
                    <span v-if="sub.badge" class="ms-auto text-xs">{{ sub.badge }}</span>
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </SidebarMenuItem>
</template>
