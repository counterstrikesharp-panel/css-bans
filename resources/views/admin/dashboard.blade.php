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
                    You are using the latest version {{env('APP_VERSION')}}.
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
                                <h3>{{$totalServers}}</h3>
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
                                <h3>{{$totalBans}}</h3>
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
                                <h3>{{$totalMutes}}</h3>
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
                                <h3>{{$totalAdmins}}</h3>
                                <p class="mb-0">Admins</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Servers</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
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
                            <table class="table table-hover">
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
                            <table class="table table-hover">
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
