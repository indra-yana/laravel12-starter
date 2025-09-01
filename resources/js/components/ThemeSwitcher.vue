<script setup lang="ts">
import { Appearance, useAppearance } from '@/composables/useAppearance';
import { Button } from '@/components/ui/button';
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { Popover, PopoverContent, PopoverTrigger, } from '@/components/ui/popover';
import { ref } from 'vue';
import AppearanceTabs from './AppearanceTabs.vue';

const { appearance } = useAppearance();
const currentAppearance = ref(appearance);

function handleThemeUpdated(value: Appearance) {
    currentAppearance.value = value;
}

</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" class="rounded-full shadow size-9 bg-backdrop-blur-sm">
                <component v-if="currentAppearance == 'light'" :is="Sun" />
                <component v-else-if="currentAppearance == 'dark'" :is="Moon" />
                <component v-else-if="currentAppearance == 'system'" :is="Monitor" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto">
            <div class="grid gap-4">
                <div class="space-y-2">
                    <h4 class="font-medium leading-none">
                        Appearance settings
                    </h4>
                    <p class="text-sm text-muted-foreground">
                        Update appearance settings
                    </p>
                </div>
                <div class="grid gap-2 w-fit">
                    <AppearanceTabs @theme-updated="handleThemeUpdated" />
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>