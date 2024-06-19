@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
       {{ __('dashboard.title') }} - CSS-BANS
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        @vite(['resources/scss/light/assets/components/modal.scss'])
        @vite(['resources/scss/dark/assets/components/modal.scss'])
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->
        <section>
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
            @if($activeBans)
                <div class="alert alert-arrow-left alert-icon-left alert-light-danger alert-dismissible fade show mb-4" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" data-bs-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <strong>Banned!</strong> You have active Bans on <b>{{$activeBans}}</b> servers
                </div>
            @endif
            @if($activeMutes)
                <div class="alert alert-arrow-left alert-icon-left alert-light-warning alert-dismissible fade show mb-4" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" data-bs-dismiss="alert" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <strong>Muted!</strong> You have active Mutes on <b>{{$activeMutes}}</b> servers
                </div>
            @endif
            @if(PermissionsHelper::isSuperAdmin())
                <div class="note note-primary mb-3">
                    <strong>CSS-BANS</strong>
                    @if(!empty($updates))
                        <h1>{{ __('dashboard.newUpdatesAvailable') }}</h1>
                        <p>Version: {{ $updates['version'] }}</p>
                        <div>{!! $updates['notes'] !!}</div>
                    @else
                        {{ __('dashboard.versionText') }} {{config('app.version')}}.
                    @endif
                    <small>{{ __('dashboard.versionVisible') }}</small>
                    <ul class="list-unstyled">
                        <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>{{ __('dashboard.banMangement') }}</li>
                        <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>{{ __('dashboard.muteMangement') }}</li>
                        <li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>{{ __('dashboard.adminManagement') }}</li>
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
                                    <p class="mb-0">{{ __('dashboard.servers') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3  mb-3">
                    <div class="card">
                        <div class="card-body">
                            <span  class="dash-active-stat badge badge-light-danger mb-2 me-4">{{$totalActiveBans}} Active</span>
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-ban text-danger fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h1>{{$totalBans}}</h1>
                                    <p class="mb-0">{{ __('dashboard.bans') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3  mb-3">
                    <div class="card">
                        <div class="card-body">
                            <span  class="dash-active-stat badge badge-light-warning mb-2 me-4">{{$totalActiveMutes}} Active</span>
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-volume-mute text-success fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h1>{{$totalMutes}}</h1>
                                    <p class="mb-0">{{ __('dashboard.mutes') }}</p>
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
                                    <p class="mb-0">{{ __('dashboard.admins') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if(!empty($topPlayersData))
            <section class="mb-4">
                <div class="card top-players ">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0 text-center">
                            <strong>{{ __('dashboard.topPlayers') }} <span class="badge badge-info">5 {{ __('dashboard.of') }} {{$topPlayersData['totalPlayers']}} {{ __('dashboard.players') }}</span></strong>
                        </h5>
                    </div>
                    <table class="table-responsive table align-middle mb-0 table-borderless">
                        <thead class="bg-light">
                        <tr>
                            <th width="25px">{{ __('dashboard.position') }}</th>
                            <th>{{ __('dashboard.player') }}</th>
                            <th>{{ __('dashboard.csRating') }}</th>
                            <th>{{ __('dashboard.rank') }}</th>
                            <th><i class="fas fa-skull-crossbones"></i> {{ __('dashboard.kills') }} </th>
                            <th><i class="fas fa-skull"></i> {{ __('dashboard.deaths') }} </th>
                            <th><i class="fas fa-trophy"></i> {{ __('dashboard.won') }}</th>
                            <th><i class="fas fa-times-circle"></i> {{ __('dashboard.lost') }} </th>
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
                                            <p class="text-muted mb-0">{{ __('dashboard.lastSeen') }}: <span class="badge badge-light-info rounded-pill d-inline">{{ \Carbon\Carbon::parse($player->k4stats->lastseen)->diffForHumans() }}</span></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {!! $player->ratingImage !!}
                                </td>
                                <td>{!! $player->rank !!}</td>
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
                        <strong>{{ __('dashboard.servers') }}</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap table-borderless">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('dashboard.server') }}</th>
                                <th scope="col">{{ __('dashboard.players') }}</th>
                                <th scope="col">{{ __('dashboard.ip') }}</th>
                                <th scope="col">{{ __('dashboard.map') }}</th>
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
        <section class="recent-stats">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0 text-center">
                                <strong>{{ __('dashboard.recentBans') }}</strong>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="th-lg">{{ __('dashboard.player') }}</th>
                                        <th scope="col" class="th-lg">{{ __('dashboard.steam') }}</th>
                                        <th scope="col" class="th-lg">{{ __('dashboard.added') }}</th>
                                        <th scope="col" class="th-lg">{{ __('dashboard.expires') }}</th>
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
                                <strong>{{ __('dashboard.recentMutes') }}</strong>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="th-lg">{{ __('dashboard.player') }}</th>
                                        <th scope="col" class="th-lg">{{ __('dashboard.steam') }}</th>
                                        <th scope="col" class="th-lg">{{ __('dashboard.added') }}</th>
                                        <th scope="col" class="th-lg">{{ __('dashboard.expires') }}</th>
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
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <x-modal :title="''" :body="''"/>
        @vite(['resources/js/dashboard/recent.ts'])
        @vite(['resources/js/dashboard/servers.ts'])

    </x-slot>
        <script>
            function getPlayerInfoUrl(serverId) {
                return "{!! env('VITE_SITE_DIR') !!}/servers/"+serverId+"/players";
            }
            function showModal(){
                $("#modal").modal('show');
            }
            const serversListUrl = '{!! env('VITE_SITE_DIR') !!}/servers';
            const recentBansUrl = '{!! env('VITE_SITE_DIR') !!}/bans';
            const recentMutesUrl = '{!! env('VITE_SITE_DIR') !!}/mutes';
            const playerActionUrl = '{!! env('VITE_SITE_DIR') !!}/players/action';
            const ranksListUrl = '{!! env('VITE_SITE_DIR') !!}/list/ranks';
        </script>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>
