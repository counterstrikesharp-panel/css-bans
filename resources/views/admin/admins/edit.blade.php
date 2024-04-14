@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Edit Admin</h5>
                    <form action="{{ route('admin.update', ['player_steam' => $admin->first()->player_steamid]) }}" method="POST">
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

                        <!-- Permissions Checkboxes -->
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

                        <!-- Ends On -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label for="ends"><b>Ends On</b></label>
                            <input type="date" id="ends" name="ends" class="form-control" value="{{ date('Y-m-d', strtotime($admin->first()->ends)) }}" required>
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
