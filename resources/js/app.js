import './bootstrap';
import '../css/app.css';
import './bootstrap';
import './theme.js';
import 'floating-vue/dist/style.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import FloatingVue from 'floating-vue';
import { trans } from 'matice';
import { createPinia } from 'pinia';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(FloatingVue)
            .use(pinia)
            .mixin({
                methods: {
                    $t: trans
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
