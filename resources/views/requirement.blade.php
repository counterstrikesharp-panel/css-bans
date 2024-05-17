<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        Requirements - CSS-BANS
        </x-slot>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
            <!--  BEGIN CUSTOM STYLE FILE  -->
            <style>
                .header-container {
                    display:none;
                }
                .sidebar-wrapper{
                    display: none;
                }
                #content {
                    margin-left: 0 !important
                }
            </style>
            <!--  END CUSTOM STYLE FILE  -->
            </x-slot>
            <!-- END GLOBAL MANDATORY STYLES -->

            <div class="row layout-top-spacing">
            <?php
                $latestLaravelVersion = '10.0';
                $laravelVersion = (isset($_GET['v'])) ? (string)$_GET['v'] : $latestLaravelVersion;

                if (!in_array($laravelVersion, array('4.2', '5.0', '5.1', '5.2', '5.3', '5.4', '5.5', '5.6', '5.7', '5.8', '6.0', '7.0', '8.0', '10.0'))) {
                $laravelVersion = $latestLaravelVersion;
                }

                $reqList = array(
                '10.0' => array(
                'php' => '8.1.0',
                'bcmath' => true,
                'ctype' => true,
                'curl' => true,
                'dom' => true,
                'fileinfo' => true,
                'json' => true,
                'mbstring' => true,
                'openssl' => true,
                'pcre' => true,
                'pdo' => true,
                'tokenizer' => true,
                'xml' => true
                ),
                );

                $strOk = '<i class="fa fa-check"></i>';
                $strFail = '<i style="color: red" class="fa fa-times"></i>';
                $strUnknown = '<i class="fa fa-question"></i>';

                $requirements = array();

                // PHP Version
                $requirements['php_version'] = version_compare(PHP_VERSION, $reqList[$laravelVersion]['php'], ">=");

                // Check PHP Extensions
                foreach ($reqList[$laravelVersion] as $extension => $required) {
                if ($extension !== 'php') {
                $requirements[$extension . '_enabled'] = extension_loaded($extension);
                }
                }

                // Determine if all requirements are met
                $allRequirementsMet = true;
                foreach ($requirements as $requirement) {
                if ($requirement === false) {
                $allRequirementsMet = false;
                break;
                }
                }

                ?>

                <!doctype html>
                <html lang="en" style="display: block">
                <head>
                    <meta charset="UTF-8">
                    <title>Server Requirements</title>
                    <link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f0f0f0;
                        }
                        .container {
                            width: 80%;
                            margin: 0 auto;
                        }
                        .requirement {
                            padding: 10px;
                            margin-bottom: 10px;
                            background-color: #fff;
                            border: 1px solid #ddd;
                        }
                        .requirement.ok {
                            border-color: #008000;
                        }
                        .requirement.fail {
                            border-color: #a00;
                        }
                    </style>
                </head>
                <body>
                <div class="container">
                    <h1>Server Requirements</h1>
                    <h3>Setup simpleAdmin</h3>
                    <p>Please make sure simpleAdmin is properly configured with the database before proceeding.</p>
                    <p>Follow the installation instructions to set up simpleAdmin with the database.</p>
                    <p>Once simpleAdmin setup is completed, you can return to the panel.</p>
                    <p>PHP Version: <?php echo $requirements['php_version'] ? $strOk : $strFail; ?> (<?php echo PHP_VERSION; ?>)</p>
                    <?php foreach ($requirements as $key => $value): ?>
                        <?php if ($key !== 'php_version'): ?>
                    <div class="requirement <?php echo $value ? 'ok' : 'fail'; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $key)); ?>: <?php echo $value ? $strOk : $strFail; ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($allRequirementsMet): ?>
                    <div style="text-align: center;"><a href="{{env('VITE_SITE_DIR')}}/setup" class="btn btn-success">NEXT</a></div>
                    <?php endif; ?>
                </div>

            </div>

            <!--  BEGIN CUSTOM SCRIPTS FILE  -->
            <x-slot:footerFiles>

            </x-slot>
                <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>

