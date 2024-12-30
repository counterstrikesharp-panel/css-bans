@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('admins.VIP') }} - CSS-BANS
    </x-slot>
    <x-slot:headerFiles>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        @if(PermissionsHelper::hasVipEditPermission())
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">{{ __('admins.editVIP') }}</h5>
                        <form action="{{ route('vip.update', $vip->account_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-outline mb-3">
                                <label class="form-label" for="account_id">{{ __('admins.steam') }}</label>
                                <input readonly type="text" class="form-control" id="account_id" name="account_id" value="{{ $vip->account_id }}" required/>
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="name">{{ __('admins.playerName') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $vip->name }}" required/>
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="sid">{{ __('admins.selectServers') }}</label>
                                <select class="form-control select2" id="sid" name="sid" required>
                                    @foreach($servers as $server)
                                        <option value="{{ $server->serverId }}" {{ $server->serverId == $vip->sid ? 'selected' : '' }}>
                                            {{ $server->serverIp }}:{{ $server->port }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="group">{{ __('admins.group') }}</label>
                                <input type="text" class="form-control" id="group" name="group" value="{{ $vip->group }}" required/>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"  {{ $vip->expires == 0 ? 'checked' : '' }}  type="checkbox" id="permanent" name="permanent">
                                    <label class="form-check-label" for="permanent">
                                        {{ __('admins.permanent') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="expires">{{ __('admins.endsOn') }}</label>
                                <input type="date" {{ $vip->expires == 0 ? 'disabled' : 'required' }} class="form-control" id="expires" name="expires" value="{{ $vip->expires != 0 ? $vip->ends : null }}" required/>
                            </div>
                            <div class="mt-3">
                                <center><button type="submit" class="btn btn-primary col-md-2 mx-auto">{{ __('admins.updateVIP') }}</button></center>
                            </div>
                        </form>
                    </div>
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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/vip/create.ts'])
    </x-slot>
</x-base-layout>