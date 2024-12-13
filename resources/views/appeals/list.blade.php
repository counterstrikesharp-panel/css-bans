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
        {{ __('Appeals List') }}
        </x-slot>
        <x-slot:headerFiles>
        </x-slot:headerFiles>
        @auth
            @if(in_array(true, $onlyManageAdminPerms))
                <div class="container mt-5">
                    <div class="card">
                        <div class="card-header">{{ __('Appeals List') }}</div>
                        <div class="card-body" style="max-height: 500px;overflow: auto">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>{{ __('admins.id') }}</th>
                                    <th>{{ __('Ban Type') }}</th>
                                    <th>{{ __('SteamID/IP') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('admins.banReason') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('admins.status') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('admins.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($appeals as $appeal)
                                    <tr>
                                        <td>{{ $appeal->id }}</td>
                                        <td>{{ $appeal->ban_type }}</td>
                                        <td>{{ $appeal->ban_type == 'Steam ID' ? $appeal->steamid : $appeal->ip }}</td>
                                        <td>{{ $appeal->name }}</td>
                                        <td>
                                            <div style="width: 200px; overflow: hidden;">
                                                <textarea rows="2" readonly style="width: 100%; border: none; resize: none; background-color: transparent; color: inherit; caret-color: transparent;">{{ $appeal->reason }}</textarea>
                                            </div>
                                        </td>
                                        <td>{{ $appeal->email }}</td>
                                        <td>
                                            <span class="badge {{ $appeal->status == 'PENDING' ? 'badge-warning' : ($appeal->status == 'APPROVED' ? 'badge-success' : 'badge-danger') }}">
                                                {{ $appeal->status }}
                                            </span>
                                        </td>
                                        <td>{{ $appeal->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ getAppSubDirectoryPath() }}/appeals/{{$appeal->id}}" class="btn btn-primary btn-sm">{{ __('View') }}</a>
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