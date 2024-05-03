@extends('layouts.app')

@section('content')
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title text-center mb-4">Delete Admin</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.delete', ['player_steam' => $admin->player_steamid]) }}" method="POST">
                        @csrf

                        <!-- Servers Multi-Select Dropdown -->
                        <div class="mb-3">
                            <select multiple="multiple"  class="form-control" id="server_ids" name="server_ids[]">
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}" {{ in_array($server->id, old('server_ids', [])) ? 'selected' : '' }}>
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
