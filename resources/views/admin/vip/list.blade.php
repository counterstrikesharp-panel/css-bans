
@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('admins.VIP') }} - CSS-BANS
        </x-slot>
        @vite(['resources/scss/dark/assets/components/datatable.scss'])

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
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
        @auth
            <section class="mb-12">
                <div class="card">
                    @if(PermissionsHelper::isSuperAdmin())
                        <div class="mt-3 d-flex justify-content-end p-1">
                            <a href="{{ route('vip.create') }}" class="col-md- btn btn-success">{{ __('Add VIP') }}</a>
                        </div>
                    @endif
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0 text-center">
                            <strong>{{ __('admins.VIP') }}</strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive display responsive nowrap">
                            <table class="table table-hover" id="vipList" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('admins.playerName') }}</th>
                                    <th scope="col">{{ __('admins.playerNick') }}</th>
                                    <th scope="col">{{ __('admins.serverName') }}</th>
                                    <th scope="col">{{ __('admins.group') }}</th>
                                    <th scope="col">{{ __('admins.endsOn') }}</th>
                                    <th scope="col">{{ __('admins.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody id="vipListBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            @else
                <!-- Login with Steam modal -->
                <div class="container">
                    <div id="loginAlert" class="alert alert-gradient  fade show" role="alert" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050;">
                        <strong>{{ __('skins.loginRequired') }}</strong> {{ __('skins.needToLogin') }}
                        <a href="{{ getAppSubDirectoryPath().'/auth/steam' }}" class="btn btn-success">
                            <i class="fab fa-steam"></i> {{ __('skins.loginWithSteam') }}
                        </a>
                    </div>
                </div>
            @endauth
                <!--  BEGIN CUSTOM SCRIPTS FILE  -->
            <x-slot:footerFiles>
                <script>
                    const vipListUrl = '{{ env('VITE_SITE_DIR') }}/vip';
                </script>
                @vite(['resources/js/vip/list.ts'])
                <script src="{{asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
            </x-slot>

</x-base-layout>
