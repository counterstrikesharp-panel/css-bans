@extends('layouts.app')
@php
    $today = \Carbon\Carbon::now()->format('Y-m-d');
@endphp
@section('content')
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
                    <h5 class="card-title text-center mb-4">Edit Admin Groups</h5>
                    @if($allowMigrate)
                        <form action="{{ route('admin.update', ['player_steam' => $admin->first()->player_steamid]) }}" method="POST">
                    @else
                        <form action="{{ route('admin.groups.update', ['player_steam' => $admin->first()->player_steamid]) }}" method="POST">
                    @endif
                        <div class="note note-info mb-3">
                            <strong>Note:</strong> Admins will not receive their permissions on the server until a map change. Alternatively, you can execute css_reloadadmins for immediate effect.
                        </div>
                        @csrf
                        <!-- Server Dropdown -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="server_id"><b>Server</b></label>
                            <select  class="form-select" id="server_id" name="server_id" required>
                                <option value="">Select Server</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}" {{ $server->id == $admin->first()->server_id ? 'selected' : '' }}>
                                        {{ $server->hostname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="player_name"><b>Player Name</b></label>
                            <input type="text" class="form-control active" value="{{$admin->first()->player_name}}" id="player_name" name="player_name" required/>
                        </div>
                        <!-- Permissions Checkboxes -->
                        @if($allowMigrate)
                            <div class="mb-3">
                                <label><b>Permissions</b></label><br>
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
                            <div class="note note-danger mb-3">
                                <strong>Note:</strong> Admin will be moved to below groups selected for ALL Servers. Existing individual permissions of admin will be removed!
                            </div>
                            <label class="form-label" for="group_id"><b>Move Admin to Groups</b></label>
                            <select multiple="multiple" class="form-select" id="group_id" name="groups[]">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ in_array($group->name, $adminGroups) ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <label class="form-label" for="group_id"><b>Groups</b></label>
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
                                    Permanent (Never Expire)
                                </label>
                            </div>
                        </div>
                        <!-- Ends On -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label for="ends"><b>Ends On</b></label>
                            <input min="{{$today}}" type="date" {{ $admin->first()->ends == null ? 'disabled' : 'required' }}   id="ends" name="ends" class="form-control" value="{{ $admin->first()->ends != null ? date('Y-m-d', strtotime($admin->first()->ends)) : '' }}" >
                        </div>
                        <div class="form-group">
                            <label for="immunity">Immunity</label>
                            <input type="number" id="immunity" name="immunity"  class="form-control" value="{{ $admin->first()->immunity }}" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/admin/edit.ts'])
