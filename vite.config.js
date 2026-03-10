import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/Admin/events/dashboard.js',
                'resources/js/Admin/stores/dashboard.js',
                'resources/js/Admin/users/dashboard.js',
                'resources/js/layouts/admin.js',
                'resources/js/layouts/participant.js',
                'resources/js/participant/cart/index.js',
                'resources/js/participant/stores/index.js',
                'resources/js/participant/stores/show.js',
                'resources/js/participant/wishlist/index.js',
                'resources/js/participant/dashboard.js',
                'resources/js/store/queue/index.js',
                'resources/js/store/refund/index.js',
                'resources/js/store/dashboard.js',
                'resources/js/store/settings.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
