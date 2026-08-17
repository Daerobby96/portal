import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Initialize Alpine.js for existing Blade views
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Global helper for opening modals in Blade
window.openModal = (modalId) => {
    window.dispatchEvent(new CustomEvent(`open-modal-${modalId}`));
};

const appName = import.meta.env.VITE_APP_NAME || 'ERP-POLKA';

// Initialize Inertia Vue 3 if Inertia root element is present
const el = document.getElementById('app');
if (el && el.hasAttribute('data-page')) {
    createInertiaApp({
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue')
            ),
        setup({ el, App, props, plugin }) {
            return createApp({ render: () => h(App, props) })
                .use(plugin)
                .mount(el);
        },
        progress: {
            color: '#4f46e5',
            showSpinner: true,
        },
    });
}
