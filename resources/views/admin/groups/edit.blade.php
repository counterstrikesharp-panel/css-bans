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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Edit Group</h5>
                    <form action="{{ route('group.update', ['id' => $groupDetails->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="note note-info mb-3">
                            <strong>Note:</strong> Adding permissions to an existing group for new servers will append the new permissions to the existing set, applying for all associated servers.
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <input value="{{$groupDetails->name}}" placeholder="Should Start with #" type="text" class="form-control" id="group_name" name="name" required/>
                            <label class="form-label" for="group_name">Group Name</label>
                        </div>
                        <hr/>
                        <div class="mb-3">
                            <label>Permissions</label><br>
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input {{ in_array($permission->permission, $groupPermissions) ? 'checked' : '' }} class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->permission }}" id="permission{{ $permission->id }}">
                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                        {{ $permission->description }} ({{ $permission->permission }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="immunity">Immunity</label>
                            <input type="number" id="immunity" name="immunity"  class="form-control" value="{{ $groupDetails->immunity }}" required>
                        </div>
                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-3 mx-auto ">Update Group</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/groups/edit.ts'])
