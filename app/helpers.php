<?php

if (! function_exists('layoutConfig')) {
    function layoutConfig() {

        if (Request::is('modern-light-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.vlm');

        } else if (Request::is('modern-dark-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.vdm');

        } else if (Request::is('collapsible-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.cm');

        } else if (Request::is('horizontal-light-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.hlm');

        } else if (Request::is('horizontal-dark-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.hlm');

        }

        // RTL

        else if (Request::is('rtl/modern-light-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.vlm-rtl');

        } else if (Request::is('rtl/modern-dark-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.vdm-rtl');

        } else if (Request::is('rtl/collapsible-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.cm-rtl');

        } else if (Request::is('rtl/horizontal-light-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.hlm-rtl');

        } else if (Request::is('rtl/horizontal-dark-menu/*')) {

            $__getConfiguration = Config::get('app-config.layout.hdm-rtl');

        }



        // Login

        else if (Request::is('login')) {

            $__getConfiguration = Config::get('app-config.layout.vlm');

        } else {
            $__getConfiguration = Config::get('barebone-config.layout.bb');
        }

        return $__getConfiguration;
    }
}


if (!function_exists('getRouterValue')) {

    function getRouterValue() {

        if (Request::is('modern-light-menu/*')) {

            $__getRoutingValue = '/modern-light-menu';

        } else if (Request::is('modern-dark-menu/*')) {

            $__getRoutingValue = '/modern-dark-menu';

        } else if (Request::is('collapsible-menu/*')) {

            $__getRoutingValue = '/collapsible-menu';

        } else if (Request::is('horizontal-light-menu/*')) {

            $__getRoutingValue = '/horizontal-light-menu';

        } else if (Request::is('horizontal-dark-menu/*')) {

            $__getRoutingValue = '/horizontal-dark-menu';

        }

        // RTL

        else if (Request::is('rtl/modern-light-menu/*')) {

            $__getRoutingValue = '/rtl/modern-light-menu';

        } else if (Request::is('rtl/modern-dark-menu/*')) {

            $__getRoutingValue = '/rtl/modern-dark-menu';

        } else if (Request::is('rtl/collapsible-menu/*')) {

            $__getRoutingValue = '/rtl/collapsible-menu';

        } else if (Request::is('rtl/horizontal-light-menu/*')) {

            $__getRoutingValue = '/rtl/horizontal-light-menu';

        } else if (Request::is('rtl/horizontal-dark-menu/*')) {

            $__getRoutingValue = '/rtl/horizontal-dark-menu';

        }

        // Login

        else if (Request::is('login')) {

            $__getRoutingValue = '/modern-light-menu';

        } else {
            $__getRoutingValue = '';
        }


        return $__getRoutingValue;
    }

    if (!function_exists('getAppSubDirectoryPath')) {

    function getAppSubDirectoryPath() {
        return env('VITE_SITE_DIR');
        }
    }
}
