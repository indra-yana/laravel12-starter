<script setup lang="ts">
import { checkIsActive } from "@/lib/utils";
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";
import { NavItem } from "@/types";
import { SidebarMenuButton, useSidebar } from "@/components/ui/sidebar";
import NavBadge from "./NavBadge.vue";
import SidebarMenuItem from "@/components/ui/sidebar/SidebarMenuItem.vue";

interface Props {
    item: NavItem;
    href: string;
}

defineProps<Props>();
const { state, setOpenMobile } = useSidebar();

</script>

<template>
    <SidebarMenuItem>
        <SidebarMenuButton as-child :is-active="checkIsActive(href, item)" :tooltip="item.title" :class="{'my-2': state === 'collapsed'}">
            <Link :href="href" @click="setOpenMobile(false)">
                <component :is="item.icon" v-if="item.icon" />
                <span>{{ item.title }}</span>
                <NavBadge v-if="item.badge">{{ item.badge }}</NavBadge>
            </Link>
        </SidebarMenuButton>
    </SidebarMenuItem>
</template>
