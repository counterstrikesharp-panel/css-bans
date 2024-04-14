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
                    <h5 class="card-title text-center mb-4">Add Mute</h5>
                    <form action="{{ route('mute.store') }}" method="POST">
                        @csrf
                        <div data-mdb-input-init class="form-outline mb-3">
                            <input type="number" class="form-control" id="player_steam_id" name="player_steam_id" required/>
                            <label class="form-label" for="player_steam_id">Player Steam ID</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <textarea type="text" class="form-control" id="reason" name="reason" required></textarea>
                            <label class="form-label" for="reason">Reason</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <input  type="datetime-local"  min="{{ date('Y-m-d\TH:i') }}" class="form-control active" id="duration" name="duration" required/>
                            <label class="form-label" for="duration">Duration</label>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <select multiple="multiple" class="form-select" id="server_ids" name="server_ids[]" required>
                                <option value="">Select Servers</option>
                                @foreach($servers as $server)
                                    <option  value="{{ $server->id }}">{{ $server->hostname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto">Add Mute</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@vite(['resources/js/bans/add.ts'])
