<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        Logs - CSS-BANS
        </x-slot>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
            <style>
                pre {
                    background: black;
                    color: white;
                    text-wrap: balance;
                }
            </style>
        </x-slot>
    <div class="row layout-top-spacing">
        <pre>{{ $logContent }}</pre>
    </div>
            <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>

    </x-slot>
                <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>


