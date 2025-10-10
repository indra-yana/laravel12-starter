import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { initializeTheme } from './composables/useAppearance';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import type { DefineComponent } from 'vue';
import lang from '@/plugins/lang';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    resolve: (name => {
        let parts = name.split('::');
        let type = parts.length > 1;

        if (type) {
            let nameVue = parts[1].split('.')[0];
            return resolvePageComponent([
                `./pages/${name}.vue`,
                `../../Modules/${parts[0]}/resources/assets/js/pages/${nameVue}.vue`,
                `../../Modules/${parts[0]}/resources/assets/js/components/*.vue`,
                `../../Modules/${parts[0]}/resources/assets/js/layouts/*.vue`,
            ],
                import.meta.glob<DefineComponent>([`../../Modules/**/resources/assets/js/pages/**/*.vue`, './pages/**/*.vue'])
            );
        }

        return resolvePageComponent([
            `./pages/${name}.vue`,
        ],
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        );
    }),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        pinia.use(piniaPluginPersistedstate);

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .use(lang)
            .mount(el);
    },
    progress: {
        color: '#facc15',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
