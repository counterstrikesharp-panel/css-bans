@extends('layouts.app')
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
    @php
        $today = \Carbon\Carbon::now()->format('Y-m-d');
    @endphp
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Add New Admin</h5>
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="number" class="form-control" id="steam_id" name="steam_id" required/>
                            <label class="form-label" for="steam_id">Steam ID</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="text" class="form-control" id="player_name" name="player_name" required/>
                            <label class="form-label" for="player_name">Player Name</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <select multiple="multiple" class="form-select" id="server_id" name="server_ids[]" required>
                                <option value="">Select Server</option>
                                <option value="all">All Servers</option>
                                @foreach($servers as $server)
                                        <option  value="{{ $server->id }}">{{ $server->hostname }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" checked type="radio" name="permission_type" id="flagsPermission" value="flags" />
                            <label class="form-check-label" for="flagsPermission">Permissions</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permission_type" id="groups" value="groups" />
                            <label class="form-check-label" for="groups">Groups</label>
                        </div>

                        <div class="mb-3 flags">
                            <hr/>
                            <label>Permissions</label><br>
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
                            <label class="form-label" for="group_id"><b>Groups</b></label>
                            <select multiple="multiple" class="form-select" id="group_id" name="groups[]">play
                                <option value="">Select Group</option>
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
                                    Permanent (Never Expire)
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ends">Ends On</label>
                            <input type="date" id="ends" name="ends" min="{{$today}}" class="form-control" value="{{ old('ends') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="immunity">Immunity</label>
                            <input type="number" id="immunity" name="immunity"  class="form-control" value="{{ old('immunity') }}" required>
                        </div>
                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto ">Add Admin</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/admin/create.ts'])
