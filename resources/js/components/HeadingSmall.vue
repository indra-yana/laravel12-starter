<script setup lang="ts">
import Separator from './ui/separator/Separator.vue';

interface Props {
    title: string;
    description?: string;
    separator?: boolean;
}

withDefaults(defineProps<Props>(), {
    separator: true,
})

</script>

<template>
    <header>
        <h3 class="mb-0.5 text-base font-medium">{{ title }}</h3>
        <p v-if="description" class="text-sm text-muted-foreground">
            {{ description }}
        </p>
        <Separator v-if="separator" class="mt-4 hidden lg:block" />
    </header>
</template>
