<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('dashboard.mutes') }} - CSS-BANS
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
                    <h5 class="card-title text-center mb-4">{{ __('admins.editMute') }}</h5>
                    <form action="{{ route('mute.update', ['id' => $mute->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form mb-3">
                            <label class="form-label" for="player_steam_id">{{ __('admins.playerSteam') }}</label>
                            <input type="number" class="form-control" id="player_steam_id" name="player_steam_id" value="{{ $mute->player_steamid }}" required/>
                        </div>
                        <div  class="form mb-3">
                            <label class="form-label" for="type">{{ __('admins.muteType') }}</label>
                            <select class="form-select" id="type" name="type">
                                <option {{($mute->type == 'GAG') ?'selected' : ''}} value="GAG">GAG</option>
                                <option {{($mute->type == 'MUTE') ?'selected' : ''}} value="MUTE">MUTE</option>
                                <option {{($mute->type == 'SILENCE') ?'selected' : ''}} value="SILENCE">SILENCE</option>
                            </select>
                        </div>
{{--                        disabled for future use once plugin supports--}}
{{--                        <div class="form mb-3">--}}
{{--                            <label class="form-label" for="player_ip">Player IP</label>--}}
{{--                            <input type="text" class="form-control" id="player_ip" name="player_ip" value="{{ $mute->player_ip }}"/>--}}
{{--                        </div>--}}
{{--                        <div class="form mb-3">--}}
{{--                            <label class="form-label" for="player_name">Player Name (Required only if no steam id is specified)</label>--}}
{{--                            <input type="text" class="form-control" id="player_name" name="player_name" value="{{ $mute->player_name }}"/>--}}
{{--                        </div>--}}
                        <div class="form mb-3">
                            <label class="form-label" for="reason">{{ __('admins.banReason') }}</label>
                            <textarea type="text" class="form-control" id="reason" name="reason" required>{{ $mute->reason }}</textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="permanent" name="permanent" {{ $mute->duration == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="permanent">
                                {{ __('admins.permanent') }}
                            </label>
                        </div>

                        <div class="form mb-3">
                            <label class="form-label" for="duration">{{ __('admins.banDuration') }}</label>

                            <input
                                type="datetime-local"
                                class="form-control active"
                                id="duration"
                                name="duration"
                                value="{{ $mute->ends ? \Carbon\Carbon::parse($mute->ends)->format('Y-m-d\TH:i') : '' }}"
                                required
                            {{ $mute->duration == 0 ? 'disabled' : '' }} />
                        </div>

                        <div class="mb-3">
                            <label for="server_name" class="form-label">{{ __('admins.serverName') }}</label>
                            <input type="text" class="form-control" id="server_name" name="server_name" value="{{ $mute->server->hostname }}" readonly>
                        </div>

                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto">{{ __('admins.updateMute') }}</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/mutes/add.ts'])
    </x-slot>
</x-base-layout>
@vite(['resources/js/mutes/add.ts'])
