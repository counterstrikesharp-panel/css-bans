<x-base-layout :scrollspy="false">
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
    @php
        $today = \Carbon\Carbon::now()->format('Y-m-d');
    @endphp
    <x-slot:pageTitle>
        {{ __('admins.title') }} - CSS-BANS
    </x-slot>
    <x-slot:headerFiles>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">{{ __('admins.add') }}</h5>
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="alert alert-gradient alert-dismissible fade show mb-4" role="alert">
                            <strong>{{ __('admins.note') }}</strong> {{ __('admins.noteMessage') }}
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="steam_id">{{ __('admins.steam') }}</label>
                            <input type="number" class="form-control" id="steam_id" name="steam_id" required/>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="player_name">{{ __('admins.playerName') }}</label>
                            <input type="text" class="form-control" id="player_name" name="player_name" required/>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <select multiple="multiple" class="form-select" id="server_id" name="server_ids[]" required>
                                <option value="">{{ __('admins.selectServers') }}</option>
                                <option value="all">{{ __('admins.allServers') }}</option>
                                @foreach($servers as $server)
                                        <option  value="{{ $server->id }}">{{ $server->hostname }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" checked type="radio" name="permission_type" id="flagsPermission" value="flags" />
                            <label class="form-check-label" for="flagsPermission">{{ __('admins.Permissions') }}</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permission_type" id="groups" value="groups" />
                            <label class="form-check-label" for="groups">{{ __('admins.Groups') }}</label>
                        </div>

                        <div class="mb-3 flags">
                            <hr/>
                            <label>{{ __('admins.Permissions') }}</label><br>
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}">
                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                        {{ $permission->description }} ({{ $permission->permission }})
                                    </label>
                                </div>
                            @endforeach
                            <hr/>
                        </div>
                        <div class="mb-3 groups" style="display:none">
                            <label class="form-label" for="group_id"><b>{{ __('admins.Groups') }}</b></label>
                            <select multiple="multiple" class="form-select" id="group_id" name="groups[]">play
                                <option value="">{{ __('admins.selectGroup') }}</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permanent" name="permanent">
                                <label class="form-check-label" for="permanent">
                                    {{ __('admins.permanent') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ends">{{ __('admins.endsOn') }}</label>
                            <input type="date" id="ends" name="ends" min="{{$today}}" class="form-control" value="{{ old('ends') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="immunity">{{ __('admins.Immunity') }}</label>
                            <input type="number" id="immunity" name="immunity"  class="form-control" value="{{ old('immunity') }}" required>
                        </div>
                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto ">{{ __('admins.addAdminButton') }}</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/admin/create.ts'])
    </x-slot>
</x-base-layout>

