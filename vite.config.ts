import { defineConfig } from 'vite';
import { resolve } from 'node:path';
import collectModuleAssetsPaths from './vite-module-loader.ts';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from "@tailwindcss/vite";
import vue from '@vitejs/plugin-vue';

async function getConfig() {
    const paths = [
        'resources/css/app.css',
        'resources/js/app.ts'
    ];

    const allPaths = await collectModuleAssetsPaths(paths, 'Modules');

    return defineConfig({
        plugins: [
            laravel({
                input: allPaths,
                ssr: 'resources/js/ssr.ts',
                refresh: true,
            }),
            tailwindcss(),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        resolve: {
            alias: {
                // '@': resolve(__dirname, './Modules/Core/resources/assets/js/'),
                
                '@': path.resolve(__dirname, 'resources/js'),
                '@modules': path.resolve(__dirname, 'Modules'),
                'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
            }
        }
    });
}

export default getConfig();
