import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/main.js'],
            refresh: true,
        }),
        vue(),
        vuetify({ autoImport: true }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    appType: 'spa',
    server: {
        port: 5173,
        open: true,
        hmr: {
            host: 'localhost',
        },
        // This is crucial for SPA routing
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
            },
        },
    },
});
