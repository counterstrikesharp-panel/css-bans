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
        {{ __('Reports List') }}
        </x-slot>
        <x-slot:headerFiles>
        </x-slot:headerFiles>
        @auth
            @if(in_array(true, $onlyManageAdminPerms))
                <div class="container mt-5">
                    <div class="card">
                        <div class="card-header">{{ __('Reports List') }}</div>
                        <div class="card-body" style="max-height: 500px;overflow: auto">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('SteamID/IP') }}</th>
                                    <th>{{ __('Nickname') }}</th>
                                    <th>{{ __('dashboard.server') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('admins.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->ban_type == 'Steam ID' ? $report->steamid : $report->ip }}</td>
                                        <td>{{ $report->nickname }}</td>
                                        <td>{{ $report->server->hostname }}</td>
                                        <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ getAppSubDirectoryPath() }}/reports/show/{{$report->id}}" class="btn btn-primary btn-sm">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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