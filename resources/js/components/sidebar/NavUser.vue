<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ChevronsUpDown } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { SidebarMenuButton, useSidebar } from '@/components/ui/sidebar';
import { type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import UserInfo from '@/components/UserInfo.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';

defineProps<{
    asThumb?: boolean,
}>()

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const { isMobile, state } = useSidebar();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button v-if="asThumb" variant="outline" class="rounded-full shadow-md size-9 bg-backdrop-blur-sm">
                <UserInfo :user="user" />
            </Button>
            <SidebarMenuButton v-else size="lg" class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground border-2">
                <UserInfo :user="user" :showName="true" />
                <ChevronsUpDown class="ml-auto size-4" />
            </SidebarMenuButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg" :side="isMobile ? 'bottom' : state === 'collapsed' ? 'left' : 'bottom'" align="end" :side-offset="4">
            <UserMenuContent :user="user" />
        </DropdownMenuContent>
    </DropdownMenu>
</template>
