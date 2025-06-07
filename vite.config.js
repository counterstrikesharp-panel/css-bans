import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { hash } from './resources/js/fn.js';

export default defineConfig({
    plugins: [
        laravel({
            input: [

                /**
                 * ================================
                 *      Will Config images Filesb
                 * =================================
                 */
                'resources/js/app.js',

                /**
                 * =======================
                 *      Assets Files
                 * =======================
                 */

                // Loader
                'resources/scss/layouts/modern-light-menu/light/loader.scss',
                'resources/layouts/modern-light-menu/loader.js',
                'resources/layouts/modern-dark-menu/loader.js',
                'resources/layouts/collapsible-menu/loader.js',
                'resources/layouts/horizontal-light-menu/loader.js',
                'resources/layouts/horizontal-dark-menu/loader.js',

                // Structure

                // Modern Light Menu
                'resources/scss/layouts/modern-light-menu/light/structure.scss',
                'resources/scss/layouts/modern-light-menu/dark/structure.scss',

                // Modern Dark Menu
                'resources/scss/layouts/modern-dark-menu/light/structure.scss',
                'resources/scss/layouts/modern-dark-menu/dark/structure.scss',

                // Collapsible Menu
                'resources/scss/layouts/collapsible-menu/light/structure.scss',
                'resources/scss/layouts/collapsible-menu/dark/structure.scss',

                // Horizontal Light Menu
                'resources/scss/layouts/horizontal-light-menu/light/structure.scss',
                'resources/scss/layouts/horizontal-light-menu/dark/structure.scss',

                // Horizontal Dark Menu
                'resources/scss/layouts/horizontal-dark-menu/light/structure.scss',
                'resources/scss/layouts/horizontal-dark-menu/dark/structure.scss',



                // Main
                'resources/scss/light/assets/main.scss',
                'resources/scss/dark/assets/main.scss',

                // Secondary Files
                'resources/scss/light/assets/scrollspyNav.scss',
                'resources/scss/light/assets/custom.scss',

                'resources/scss/dark/assets/scrollspyNav.scss',
                'resources/scss/dark/assets/custom.scss',

                // --- Componenets
                'resources/scss/light/assets/components/accordions.scss',
                'resources/scss/light/assets/components/carousel.scss',
                'resources/scss/light/assets/components/flags.scss',
                'resources/scss/light/assets/components/font-icons.scss',
                'resources/scss/light/assets/components/list-group.scss',
                'resources/scss/light/assets/components/media_object.scss',
                'resources/scss/light/assets/components/modal.scss',
                'resources/scss/light/assets/components/tabs.scss',
                'resources/scss/light/assets/components/timeline.scss',


                // --- Elements
                'resources/scss/light/assets/elements/alert.scss',
                'resources/scss/light/assets/elements/color_library.scss',
                'resources/scss/light/assets/elements/custom-pagination.scss',
                'resources/scss/light/assets/elements/custom-tree_view.scss',
                'resources/scss/light/assets/elements/custom-typography.scss',
                'resources/scss/light/assets/elements/infobox.scss',
                'resources/scss/light/assets/elements/popover.scss',
                'resources/scss/light/assets/elements/search.scss',
                'resources/scss/light/assets/elements/tooltip.scss',


                // --- Forms
                'resources/scss/light/assets/forms/switches.scss',

                // --- Pages
                'resources/scss/light/assets/pages/contact_us.scss',
                'resources/scss/light/assets/pages/faq.scss',
                'resources/scss/light/assets/pages/knowledge_base.scss',
                'resources/scss/light/assets/pages/error/error.scss',
                'resources/scss/light/assets/pages/error/style-maintanence.scss',

                /**
                 * Dark
                 */

                // --- Componenets
                'resources/scss/dark/assets/components/accordions.scss',
                'resources/scss/dark/assets/components/carousel.scss',
                'resources/scss/dark/assets/components/flags.scss',
                'resources/scss/dark/assets/components/font-icons.scss',
                'resources/scss/dark/assets/components/list-group.scss',
                'resources/scss/dark/assets/components/media_object.scss',
                'resources/scss/dark/assets/components/modal.scss',
                'resources/scss/dark/assets/components/tabs.scss',
                'resources/scss/dark/assets/components/timeline.scss',


                // --- Elements
                'resources/scss/dark/assets/elements/alert.scss',
                'resources/scss/dark/assets/elements/color_library.scss',
                'resources/scss/dark/assets/elements/custom-pagination.scss',
                'resources/scss/dark/assets/elements/custom-tree_view.scss',
                'resources/scss/dark/assets/elements/custom-typography.scss',
                'resources/scss/dark/assets/elements/infobox.scss',
                'resources/scss/dark/assets/elements/popover.scss',
                'resources/scss/dark/assets/elements/search.scss',
                'resources/scss/dark/assets/elements/tooltip.scss',


                // --- Forms
                'resources/scss/dark/assets/forms/switches.scss',

                // --- Pages
                'resources/scss/dark/assets/pages/contact_us.scss',
                'resources/scss/dark/assets/pages/faq.scss',
                'resources/scss/dark/assets/pages/knowledge_base.scss',
                'resources/scss/dark/assets/pages/error/error.scss',
                'resources/scss/dark/assets/pages/error/style-maintanence.scss',
                'resources/scss/common/common.scss',

                /**
                 * =======================
                 *      Assets JS Files
                 * =======================
                 */

                // Outer Files
                'resources/assets/js/custom.js',
                'resources/assets/js/scrollspyNav.js',

                // Forms
                'resources/assets/js/forms/bootstrap_validation/bs_validation_script.js',
                'resources/assets/js/forms/custom-clipboard.js',

                /**
                 * =======================
                 *      Plugins Files
                 * =======================
                 */

                // Importing All the Plugin Custom SCSS File ( plugins.min.scss contains all the custom SCSS/CSS. )
                // 'resources/scss/light/plugins/plugins.min.scss',

                /**
                 * Light
                 */
                'resources/scss/light/plugins/bootstrap-range-Slider/bootstrap-slider.scss',
                'resources/scss/light/plugins/bootstrap-touchspin/custom-jquery.bootstrap-touchspin.min.scss',
                'resources/scss/light/plugins/loaders/custom-loader.scss',
                'resources/scss/light/plugins/table/datatable/dt-global_style.scss',
                'resources/scss/light/plugins/table/datatable/custom_dt_custom.scss',
                'resources/scss/light/plugins/table/datatable/custom_dt_miscellaneous.scss',

                /**
                 * Dark
                 */

                'resources/scss/dark/plugins/bootstrap-range-Slider/bootstrap-slider.scss',
                'resources/scss/dark/plugins/bootstrap-touchspin/custom-jquery.bootstrap-touchspin.min.scss',
                'resources/scss/dark/plugins/loaders/custom-loader.scss',
                'resources/scss/dark/plugins/table/datatable/dt-global_style.scss',
                'resources/scss/dark/plugins/table/datatable/custom_dt_custom.scss',
                'resources/scss/dark/plugins/table/datatable/custom_dt_miscellaneous.scss',

                'resources/assets/js/scrollspyNav.js',

                'resources/layouts/modern-light-menu/app.js',
                'resources/layouts/modern-dark-menu/app.js',
                'resources/layouts/collapsible-menu/app.js',
                'resources/layouts/horizontal-light-menu/app.js',
                'resources/layouts/horizontal-dark-menu/app.js',
                'resources/scss/light/plugins/perfect-scrollbar/perfect-scrollbar.scss',
                'resources/scss/dark/assets/components/datatable.scss',
                'resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss',
                'resources/js/dashboard/recent.ts',
                'resources/js/dashboard/servers.ts',
                'resources/js/bans/bans.ts',
                'resources/js/mutes/mutes.ts',
                'resources/js/admin/admins.ts',
                'resources/js/admin/create.ts',
                'resources/js/admin/edit.ts',
                'resources/js/bans/add.ts',
                'resources/js/mutes/add.ts',
                'resources/js/mutes/add.ts',
                'resources/js/groups/list.ts',
                'resources/js/groups/groups.ts',
                'resources/js/groups/edit.ts',
                'resources/js/ranks/ranks.ts',
                'resources/js/ranks/playtime.ts',
                'resources/js/admin/delete.ts',
                'resources/js/nav.js',
                'resources/js/vip/list.ts',
                'resources/js/skins/weapons.ts',
                'resources/js/skins/gloves.ts',
                'resources/js/skins/agents.ts',
                'resources/js/skins/music.ts',
                'resources/js/skins/knives.ts',
                'resources/js/skins/pin.ts',
                'resources/js/vip/create.ts',
                'resources/scss/dark/assets/users/user-profile.scss',
                'resources/scss/dark/assets/widgets/modules-widgets.scss',
                'resources/scss/dark/plugins/apex/custom-apexcharts.scss'
            ],
            refresh: true,
        }),
    ],
    // build: {
    //     rollupOptions: {
    //       output: {
    //         assetFileNames: (assetInfo) => {
    //           let extType = assetInfo.name.split('.').at(1);
    //           if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
    //             extType = 'img';
    //           }
    //           return `assets/${extType}/[name]-[hash][extname]`;
    //         },
    //         chunkFileNames: 'assets/js/[name]-[hash].js',
    //         entryFileNames: 'assets/js/[name]-[hash].js',
    //       },
    //     },
    //   },
    // build: {
    //     rollupOptions: {
    //         output: {
    //             entryFileNames: `[name]` + hash + `.js`,
    //             chunkFileNames: `[name]` + hash + `.js`,
    //             assetFileNames: `[name]` + hash + `.[ext]`
    //         }
    //     }
    // }

    // resolve: {
    //     alias: {
    //         // '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap')
    //         '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap/dist/css/bootstrap.min.css'),
    //         '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js')
    //     }
    // }
});
