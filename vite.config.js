import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { fileURLToPath, URL } from 'node:url'; // Ensure URL is imported




export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),

        Components({
            dirs: ['resources/js/Components', 'resources/js/Layouts'],
            dts: true,
            resolvers: [
              componentName => {
              // Auto import `VueApexCharts`
                // if (componentName === 'VueApexCharts')
                //   return { name: 'default', from: 'vue3-apexcharts', as: 'VueApexCharts' }
              },
            ],
        }), // Docs: https://github.com/antfu/unplugin-auto-import#unplugin-auto-import
        AutoImport({
            imports: ['vue', '@vueuse/core', '@vueuse/math',],
            dirs: [
              './resources/js/Components/*',
            ],
            vueTemplate: true,
            // ℹ️ Disabled to avoid confusion & accidental usage
            ignore: ['useCookies', 'useStorage'],
            eslintrc: {
              enabled: true,
              filepath: './.eslintrc-auto-import.json',
            },
            dts: true,
        }),
    ],
});
