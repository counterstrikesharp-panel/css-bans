import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/admin/delete.ts',
                'resources/js/admin/edit.ts',
                'resources/js/admin/create.ts',
                'resources/js/dashboard/recent.ts',
                'resources/js/admin/admins.ts',
                'resources/js/dashboard/servers.ts',
                'resources/js/bans/bans.ts',
                'resources/js/bans/add.ts',
                'resources/js/mutes/mutes.ts',
                'resources/css/admin.css',
                'resources/css/app.css',
                'resources/css/mdb.dark.min.css',
                'resources/css/mdb.dark.rtl.min.css',
                'resources/css/mdb.min.css',
                'resources/css/mdb.rtl.min.css',
                'resources/js/bootstrap.js',
                'resources/js/mdb.es.min.js',
                'resources/js/mdb.umd.min.js',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        terserOptions: {
            // Disable terser's dead code elimination
            compress: {
                dead_code: false,
            },
        },
    },
});

