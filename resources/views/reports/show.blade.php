@php use App\Helpers\CommonHelper;use App\Helpers\PermissionsHelper; @endphp
@php
    $onlyManageAdminPerms = [
       PermissionsHelper::isSuperAdmin(),
       PermissionsHelper::hasAdminCreatePermission(),
       PermissionsHelper::hasAdminEditPermission(),
       PermissionsHelper::hasAdminDeletePermission(),
   ];
@endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Report Details') }}
        </x-slot>
        <x-slot:headerFiles>
        </x-slot:headerFiles>
        @auth
            @if(in_array(true, $onlyManageAdminPerms))
                <div class="container mt-5">
                    <div class="card">
                        <div class="card-header">{{ __('Report Details') }}</div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('Ban Type') }}</th>
                                    <td>{{ $report->ban_type }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('SteamID/IP') }}</th>
                                    <td>{{ $report->ban_type == 'Steam ID' ? $report->steamid : $report->ip }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Nickname') }}</th>
                                    <td>{{ $report->nickname }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Comments') }}</th>
                                    <td>{{ $report->comments }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $report->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('dashboard.server') }}</th>
                                    <td>{{ $report->server->hostname }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Media Link (Proof)') }}</th>
                                    <td><a href="{{ $report->media_link }}" target="_blank">{{ __('View Proof') }}</a></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created At') }}</th>
                                    <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>

                            <form method="POST" action="{{ getAppSubDirectoryPath() }}/reports/destroy/{{$report->id}}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger mt-3">{{ __('Delete Report') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
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
        <x-slot:footerFiles>
            </x-slot>
</x-base-layout>
