@extends('layouts.app')

@section('content')
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
                <div class="card-header">
                    <h5 class="card-title text-center mb-4">Delete Group</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('group.delete', ['id' => $groupDetails->id]) }}" method="POST">
                        @csrf
                        <div class="note note-info mb-3">
                            <strong>Note:</strong> If you want to delete a group, select all servers. Selecting specific servers will only remove the group from those servers but will not delete the group itself.
                        </div>
                        <div class="mb-3">
                            <label><b>Group Name:</b> {{$groupDetails->name}}</label>
                        </div>
                        <!-- Servers Multi-Select Dropdown -->
                        <div class="mb-3">
                            <select multiple="multiple"  class="form-control" id="server_ids" name="server_ids[]">
                                <option value="all">All Servers</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}">
                                        {{ $server->hostname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/admin/delete.ts'])
