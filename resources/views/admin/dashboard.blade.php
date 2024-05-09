@php use App\Helpers\PermissionsHelper; @endphp
@extends('layouts.app')

@section('content')
    @if (session('success'))
        <x-alert type="success" :message="session('success')"/>
    @endif
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif
    <section>
        @if(PermissionsHelper::isSuperAdmin())
            <div class="note note-primary mb-3">
                <strong>CSS-BANS</strong>
                @if(!empty($updates))
                    <h1>New Updates Available!</h1>
                    <p>Version: {{ $updates['version'] }}</p>
                    <div>{!! $updates['notes'] !!}</div>
                @else
                    You are using the latest version {{config('app.version')}}.
                @endif
                <small>This is only visible to you.</small>
                <ul class="list-unstyled">
                    <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>Ban Management</li>
                    <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>Mute Management</li>
                    <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>Admin Management</li>
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-xl-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-server text-info fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h1>{{$totalServers}}</h1>
                                <p class="mb-0">Servers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3  mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-ban text-danger fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h1>{{$totalBans}}</h1>
                                <p class="mb-0">Bans</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3  mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-volume-mute text-success fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h1>{{$totalMutes}}</h1>
                                <p class="mb-0">Mutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-user-shield text-primary fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h1>{{$totalAdmins}}</h1>
                                <p class="mb-0">Admins</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(!empty($topPlayersData))
        <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Top Players <span class="badge badge-info">5 of {{$topPlayersData['totalPlayers']}} Players</span></strong>
                </h5>
            </div>
            <table class="table align-middle mb-0 bg-white table-borderless">
                <thead class="bg-light">
                <tr>
                    <th width="25px">Position</th>
                    <th>Player</th>
                    <th>CS Rating</th>
                    <th>Rank</th>
                    <th><i class="fas fa-skull-crossbones"></i> Kills </th>
                    <th><i class="fas fa-skull"></i> Deaths </th>
                    <th><i class="fas fa-trophy"></i> WON</th>
                    <th><i class="fas fa-times-circle"></i> LOST </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topPlayersData['topPlayers'] as $key => $player)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{$player->avatar}}" alt="" style="width: 45px; height: 45px" class="rounded-circle"/>
                                <div class="ms-3">
                                    <p class="fw-bold mb-1"><a href="https://steamcommunity.com/profiles/{{$player->player_steamid}}/">{{ $player->name }}</p>
                                    <p class="text-muted mb-0">Last seen: <span class="badge badge-info rounded-pill d-inline">{{ \Carbon\Carbon::parse($player->lastseen)->diffForHumans() }}</span></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-success rounded-pill d-inline">{{ $player->points }}</span>
                        </td>
                        <td>{{ $player->rank }}</td>
                        <td>{{ $player->k4stats->kills }}</td>
                        <td>{{ $player->k4stats->deaths }}</td>
                        <td>{{ $player->k4stats->game_win }}</td>
                        <td>{{ $player->k4stats->game_lose }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Servers</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap table-borderless">
                        <thead>
                        <tr>
                            <th scope="col">Server</th>
                            <th scope="col">Players</th>
                            <th scope="col">IP</th>
                            <th scope="col">Port</th>
                            <th scope="col">Map</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody id="serverList">

                        </tbody>
                    </table>
                </div>
            </div>
            <x-loader id="server_list_loader" />
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0 text-center">
                            <strong>Recent Bans</strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless">
                                <thead>
                                <tr>
                                    <th scope="col" class="th-lg">Player</th>
                                    <th scope="col" class="th-lg">Steam</th>
                                    <th scope="col" class="th-lg">Added</th>
                                    <th scope="col" class="th-lg">Expires</th>
                                    <th scope="col" class="th-lg"></th>
                                </tr>
                                </thead>
                                <tbody id="recentBans">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0 text-center">
                            <strong>Recent Mutes</strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless">
                                <thead>
                                <tr>
                                    <th scope="col" class="th-lg">Player</th>
                                    <th scope="col" class="th-lg">Steam</th>
                                    <th scope="col" class="th-lg">Added</th>
                                    <th scope="col" class="th-lg">Expires</th>
                                    <th scope="col" class="th-lg"></th>
                                </tr>
                                </thead>
                                <tbody id="recentMutes">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
    <x-modal :title="''" :body="''"/>
@endsection
@vite(['resources/js/dashboard/recent.ts'])
@vite(['resources/js/dashboard/servers.ts'])
