<script setup lang="ts">
import { LockKeyholeOpen, Users } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

interface Props {
    class?: string;
}

const { class: containerClass = '' } = defineProps<Props>();
const currentRoute = route().current();

const tabs = [
    { route: 'users.index', Icon: Users, label: 'Users' },
    { route: 'permissions.index', Icon: LockKeyholeOpen, label: 'Permissions' },
] as const;

function gotoRoute(goto: string) {
    router.visit(route(goto));
}

</script>

<template>
    <div :class="['inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', containerClass]">
        <button v-for="{ route, Icon, label } in tabs" :key="route" @click="gotoRoute(route)" :class="[
            'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
            route == currentRoute
                ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
        ]">
            <component :is="Icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
