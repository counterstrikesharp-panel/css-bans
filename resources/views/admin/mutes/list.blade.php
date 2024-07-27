@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('dashboard.mutes') }} - CSS-BANS
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        @vite(['resources/scss/dark/assets/components/datatable.scss'])
        <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
        @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
    </x-slot>
    @if (session('success'))
        <x-alert type="success" :message="session('success')"/>
    @endif
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <section class="mb-12">
        <div class="card">
            @if(PermissionsHelper::hasMutePermission())
                <div class="mt-3 d-flex justify-content-end p-1">
                    <a href="{{env('VITE_SITE_DIR')}}/mute/add" class="col-md- btn btn-success">{{ __('admins.addMute') }}</a>
                </div>
            @endif
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>{{ __('Mute List') }}</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover " id="mutesList" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('admins.id') }}</th>
                            <th scope="col">{{ __('dashboard.player') }}</th>
                            <th scope="col">{{ __('admins.mutedBy') }}</th>
                            <th scope="col">{{ __('admins.banReason') }}</th>
                            <th scope="col">{{ __('admins.banDuration') }}</th>
                            <th scope="col">{{ __('admins.ends') }}</th>
                            <th scope="col">{{ __('admins.muted') }}</th>
                            <th scope="col">{{ __('dashboard.server') }}</th>
                            <th scope="col">{{ __('admins.status') }}</th>
                            <th scope="col">{{ __('admins.action') }}</th>
                            <th scope="col">{{ __('admins.progress') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
        <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script>
            function getPlayerUnMuteUrl(playerSteamid) {
                return "{!! env('VITE_SITE_DIR') !!}/players/"+playerSteamid+"/unmute";
            }
            const mutesListUrl = '{!! env('VITE_SITE_DIR') !!}/list/mutes';
            //const hasMutePermission = <?php echo json_encode(PermissionsHelper::hasMutePermission()); ?>;
            //const hasWebMuteEditPermissions = <?php echo json_encode(PermissionsHelper::hasWebMuteEditPermissions()); ?>;
            window.translations = {
                searchByPlayernameAndSteamid: "{{ __('admins.searchByPlayernameAndSteamid') }}"
            };
        </script>
        @vite(['resources/js/mutes/mutes.ts'])
        <script src="{{asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
    </x-slot>
</x-base-layout>


