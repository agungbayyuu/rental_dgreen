import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
         host: '0.0.0.0', // 👈 supaya bisa diakses dari jaringan lokal
        port: 8001,
        hmr: {
            host: '192.168.1.93', // misal '192.168.1.10'
        },
        watch: {
            ignored: ['**/vendor/**', '**/storage/**', '**/node_modules/**'],
        },
    },
});
