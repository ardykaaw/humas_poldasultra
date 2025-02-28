import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin/theme.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
            '~vendor': '/vendor',
        },
    },
    optimizeDeps: {
        include: ['tailwindcss'],
    },
    css: {
        preprocessorOptions: {
            css: {
                includePaths: ['node_modules', 'vendor'],
            },
        },
    },
});
