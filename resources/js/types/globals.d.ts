import { ComponentCustomProperties } from "vue";
import { Replacements } from '@/composables/useLang';
import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    function trans(key: string, replacements?: Replacements): string;
}

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        trans: (key: string, replacements?: Replacements) => string;
    }
}
