<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('admins.title') }} - CSS-BANS
    </x-slot>
    <x-slot:headerFiles>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot>
@php
    $today = \Carbon\Carbon::now()->format('Y-m-d');
@endphp
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
                    <h5 class="card-title text-center mb-4">{{ __('admins.editGroups') }}</h5>
                    @if($allowMigrate)
                        <form action="{{ route('admin.update', ['player_steam' => $admin->first()->player_steamid]) }}" method="POST">
                    @else
                        <form action="{{ route('admin.groups.update', ['player_steam' => $admin->first()->player_steamid]) }}" method="POST">
                    @endif
                        <div class="alert alert-gradient alert-dismissible fade show mb-4" role="alert">
                            <strong>{{ __('admins.note') }}</strong> {{ __('admins.editNoteMessage') }}
                        </div>
                        @csrf
                        <!-- Server Dropdown -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="server_id"><b>Server</b></label>
                            <select  class="form-select" id="server_id" name="server_id" required>
                                <option value="">{{ __('admins.selectServers') }}</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}" {{ $server->id == $admin->first()->server_id ? 'selected' : '' }}>
                                        {{ $server->hostname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="player_name"><b>{{ __('admins.playerName') }}</b></label>
                            <input type="text" class="form-control active" value="{{$admin->first()->player_name}}" id="player_name" name="player_name" required/>
                        </div>
                        <!-- Permissions Checkboxes -->
                        @if($allowMigrate)
                            <div class="mb-3">
                                <label><b>{{ __('admins.Permissions') }}</b></label><br>
                                    @foreach($permissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->permission }}" id="permission{{ $permission->id }}"
                                            {{ in_array($permission->permission, $adminPermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission{{ $permission->id }}">
                                            {{ $permission->description }} ({{ $permission->permission }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <hr/>
                            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                                <strong>{{ __('admins.note') }}</strong> {{ __('admins.deleteGroups') }}
                            </div>
                            <label class="form-label" for="group_id"><b>{{ __('admins.moveGroups') }}</b></label>
                            <select multiple="multiple" class="form-select" id="group_id" name="groups[]">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ in_array($group->name, $adminGroups) ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <label class="form-label" for="group_id"><b>{{ __('admins.Groups') }}</b></label>
                            <select multiple="multiple" class="form-select" id="group_id" name="groups[]">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ in_array($group->name, $adminGroups) ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input"  {{ $admin->first()->ends == null ? 'checked' : '' }}  type="checkbox" id="permanent" name="permanent">
                                <label class="form-check-label" for="permanent">
                                    {{ __('admins.permanent') }}
                                </label>
                            </div>
                        </div>
                        <!-- Ends On -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label for="ends"><b>{{ __('admins.endsOn') }}</b></label>
                            <input min="{{$today}}" type="date" {{ $admin->first()->ends == null ? 'disabled' : 'required' }}   id="ends" name="ends" class="form-control" value="{{ $admin->first()->ends != null ? date('Y-m-d', strtotime($admin->first()->ends)) : '' }}" >
                        </div>
                        <div class="form-group">
                            <label for="immunity">{{ __('admins.Immunity') }}</label>
                            <input type="number" id="immunity" name="immunity"  class="form-control" value="{{ $admin->first()->immunity }}" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{ __('admins.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/admin/edit.ts'])
    </x-slot>
</x-base-layout>

