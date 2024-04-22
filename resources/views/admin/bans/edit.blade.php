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
                    <h5 class="card-title text-center mb-4">Edit Ban</h5>
                    <form action="{{ route('ban.update', ['id' => $ban->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="number" class="form-control" id="player_steam_id" name="player_steam_id" value="{{ $ban->player_steamid }}" required/>
                            <label class="form-label" for="player_steam_id">Player Steam ID</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <textarea type="text" class="form-control" id="reason" name="reason" required>{{ $ban->reason }}</textarea>
                            <label class="form-label" for="reason">Reason</label>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="permanent" name="permanent" {{ $ban->duration == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="permanent">
                                Permanent (Never Expire)
                            </label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input
                                type="datetime-local"
                                class="form-control active"
                                id="duration"
                                name="duration"
                                value="{{ $ban->ends ? \Carbon\Carbon::parse($ban->ends)->format('Y-m-d\TH:i') : '' }}"
                                required
                            {{ $ban->duration == 0 ? 'disabled' : '' }} />                            <label class="form-label" for="duration">Duration</label>
                        </div>

                        <div class="mb-3">
                            <label for="server_name" class="form-label">Server Name</label>
                            <input type="text" class="form-control" id="server_name" name="server_name" value="{{ $ban->server->hostname }}" readonly>
                        </div>

                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto">Update Ban</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/bans/add.ts'])
