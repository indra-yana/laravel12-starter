<script setup lang="ts">
import { activeInclude, checkIsActive } from "@/lib/utils";
import { ChevronRight } from "lucide-vue-next";
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from "@/components/ui/collapsible";
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";
import { NavItem } from "@/types";
import { SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from "@/components/ui/sidebar";
import Icon from "@/components/Icon.vue";
import NavBadge from "./NavBadge.vue";
import useCan from "@/composables/useCan";
import useMenuPermissions from "@/composables/useMenuPermissions";

interface Props {
    item: NavItem;
    href: string;
}

defineProps<Props>();
const { canAny } = useCan();
const { hasPermissionForGroup } = useMenuPermissions();

</script>

<template>
    <Collapsible as-child :default-open="checkIsActive(href, item)" class="group/collapsible">
        <SidebarMenuItem v-if="hasPermissionForGroup(item) || item.enable_permission == false">
            <!-- Trigger -->
            <CollapsibleTrigger as-child>
                <SidebarMenuButton :tooltip="item.title">
                    <!-- <component :is="item.icon" v-if="item.icon" /> -->
                    <Icon :name="(item.icon as string)" v-if="item.icon" />
                    <span>{{ trans(item.title) }}</span>
                    <NavBadge v-if="item.badge">{{ item.badge }}</NavBadge>
                    <ChevronRight class="ms-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                </SidebarMenuButton>
            </CollapsibleTrigger>

            <!-- Content -->
            <CollapsibleContent>
                <SidebarMenuSub>
                    <template v-for="subItem in item.items" :key="subItem.title">
                        <SidebarMenuSubItem v-if="canAny(subItem.route!!) || subItem.enable_permission == false">
                            <SidebarMenuSubButton as-child :is-active="checkIsActive(href, subItem) || activeInclude(subItem)">
                                <Link :href="subItem.href">
                                <div :class="{ 'dark:text-accent': checkIsActive(href, subItem) }">
                                    <!-- <component :is="subItem.icon" v-if="subItem.icon" class="size-4" /> -->
                                    <Icon :name="(subItem.icon as string)" v-if="subItem.icon" />
                                </div>
                                <span>{{ trans(subItem.title) }}</span>
                                <NavBadge v-if="subItem.badge">{{ subItem.badge }}</NavBadge>
                                </Link>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                    </template>
                </SidebarMenuSub>
            </CollapsibleContent>
        </SidebarMenuItem>
    </Collapsible>
</template>
