<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('dashboard.bans') }} - CSS-BANS
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

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">{{ __('admins.addBans') }}</h5>
                    <form action="{{ route('ban.store') }}" method="POST">
                        @csrf
                        <div class="alert alert-gradient alert-dismissible fade show mb-4" role="alert">
                            <strong>{{ __('admins.note') }}</strong> {{ __('admins.banPlayerNote') }}
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="player_steam_id">{{ __('admins.playerSteam') }}</label>
                            <input type="number" class="form-control" id="player_steam_id" name="player_steam_id"/>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="player_ip">{{ __('admins.playerIp') }}</label>
                            <input type="text" class="form-control" id="player_ip" name="player_ip"/>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="player_name">{{ __('admins.playerNameNote') }}</label>
                            <input type="text" class="form-control" id="player_name" name="player_name"/>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="reason">{{ __('admins.banReason') }}</label>
                            <textarea type="text" class="form-control" id="reason" name="reason" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permanent" name="permanent">
                                <label class="form-check-label" for="permanent">
                                    {{ __('admins.permanent') }}
                                </label>
                            </div>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="duration">{{ __('admins.banDuration') }}</label>
                            <input  type="datetime-local"  min="{{ date('Y-m-d\TH:i') }}" class="form-control active" id="duration" name="duration" required/>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <select multiple="multiple" class="form-select" id="server_ids" name="server_ids[]" required>
                                <option value="">Select Servers</option>
                                <option value="all">{{__('admins.allServers')}}</option>
                                @foreach($servers as $server)
                                    @if(\App\Helpers\PermissionsHelper::hasBanPermission($server->id))
                                        <option  value="{{ $server->id }}">{{ $server->hostname }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto">{{ __('admins.addBans') }}</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/bans/add.ts'])
    </x-slot>
</x-base-layout>
