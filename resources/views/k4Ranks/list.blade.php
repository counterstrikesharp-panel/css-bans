@php use Illuminate\Support\Facades\Crypt;use Illuminate\Support\Facades\Session; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('admins.ranks') }} - CSS-BANS
        </x-slot>
        @vite(['resources/scss/dark/assets/components/datatable.scss'])
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
            <link rel="stylesheet" href="{{asset('plugins/notification/snackbar/snackbar.min.css')}}">
            @vite(['resources/scss/light/plugins/notification/snackbar/custom-snackbar.scss'])
            </x-slot>
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
            <section class="mb-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0 text-center">
                            <strong>{{ __('admins.ranks') }}</strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="rank-servers">
                            <select class="form-select" id="serverSelect">
                                @foreach ($servers as $server)
                                    <option
                                        {{$server->id == Session::get('Ranks_server') ? 'selected': ''}} value="{{ Crypt::encrypt($server->id) }}">
                                        {{ $server->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="serverSelect"
                                   class="serverSelectLabel form-label">{{ __('admins.selectServers') }}</label>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless" id="ranksList" style="width:100%">
                                <thead>
                                <tr>
                                    <th>{{ __('dashboard.position') }}</th>
                                    <th>{{ __('dashboard.player') }}</th>
                                    <th>{{ __('dashboard.csRating') }}</th>
                                    <th>{{ __('dashboard.rank') }}</th>
                                    <th>{{ __('dashboard.kills') }} <i class="fas fa-skull-crossbones"></i></th>
                                    <th>{{ __('dashboard.deaths') }} <i class="fas fa-skull"></i></th>
                                    <th>{{ __('admins.assists') }} <i class="fas fa-hands-helping"></i></th>
                                    <th>{{ __('admins.headhost') }} <i class="fas fa-bullseye"></i></th>
                                    <th>{{ __('admins.ct') }} <i class="fas fa-trophy"></i></th>
                                    <th>{{ __('admins.t') }} <i class="fas fa-trophy"></i></th>
                                    <th>{{ __('admins.overall') }} <i class="fas fa-trophy"></i></th>
                                    <th>{{ __('admins.gameswon') }} <i class="fas fa-trophy"></i></th>
                                    <th>{{ __('admins.gameslost') }} <i class="fas fa-times-circle"></i></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <x-slot:footerFiles>
                @vite(['resources/js/ranks/ranks.ts'])
                <script>
                    const ranksListUrl = '{!! env('VITE_SITE_DIR') !!}/list/ranks';
                    $(document).ready(function () {
                        $('#serverSelect').change(function () {
                            const serverId = $(this).val();
                            window.location.href = '{{ url()->current() }}' + '?server_id=' + serverId;
                        });
                    });
                    window.translations = {
                        searchByPlayernameAndSteamid: "{{ __('admins.searchByPlayernameAndSteamid') }}",
                        lastSeen: "{{ __('dashboard.lastSeen') }}"
                    };
                </script>
                </x-slot>
</x-base-layout>


