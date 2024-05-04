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
                    <h5 class="card-title text-center mb-4">Edit mute</h5>
                    <form action="{{ route('mute.update', ['id' => $mute->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="number" class="form-control" id="player_steam_id" name="player_steam_id" value="{{ $mute->player_steamid }}" required/>
                            <label class="form-label" for="player_steam_id">Player Steam ID</label>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="type">Type</label>
                            <select class="form-select" id="type" name="type">
                                <option {{($mute->type == 'GAG') ?'selected' : ''}} value="GAG">GAG</option>
                                <option {{($mute->type == 'MUTE') ?'selected' : ''}} value="MUTE">MUTE</option>
                                <option {{($mute->type == 'SILENCE') ?'selected' : ''}} value="SILENCE">SILENCE</option>
                            </select>
                        </div>
                        <div data-mdb-input-init class="mb-3">
                            <label class="form-label" for="reason">Reason</label>
                            <textarea type="text" class="form-control" id="reason" name="reason" required>{{ $mute->reason }}</textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="permanent" name="permanent" {{ $mute->duration == 0 ? 'checked' : '' }}>
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
                                value="{{ $mute->ends ? \Carbon\Carbon::parse($mute->ends)->format('Y-m-d\TH:i') : '' }}"
                                required
                            {{ $mute->duration == 0 ? 'disabled' : '' }} />                            <label class="form-label" for="duration">Duration</label>
                        </div>

                        <div class="mb-3">
                            <label for="server_name" class="form-label">Server Name</label>
                            <input type="text" class="form-control" id="server_name" name="server_name" value="{{ $mute->server->hostname }}" readonly>
                        </div>

                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto">Update mute</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/mutes/add.ts'])
