<x-base-layout :scrollspy="false">
    @if (session('success'))
        <x-alert type="success" :message="session('success')"/>
    @endif
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif
    @php if(!empty(session('serverId'))) {
        $serverId = session('serverId');
    }
    @endphp
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <x-slot:pageTitle>
        Rcon - CSS-BANS
        </x-slot>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
            <style>
                pre {
                    background: black;
                    color: white;
                    text-wrap: balance;
                    min-height: 400px;
                    border-radius: 10px;
                }
                .rcon-container {
                    margin-right: 3px;
                    margin-left: 3px;
                }
            </style>
            </x-slot>
            <div class="row layout-top-spacing rcon-containers">
                <div class="alert alert-light-success alert-dismissible fade show mb-4" role="alert">
                    <strong>{{ __('admins.note') }}</strong> {{ __('admins.rconPasswordNote') }}
                </div>
                <pre id="rcon_output">
                    {{session('data')}}
                </pre>
                <form class="form-group" action="{{ route('rcon.execute', ['server_id' => $serverId ?? $servers->first()->id]) }}" method="POST">
                    @csrf
                    <div class="col-md-12">
                        <label>{{ __('dashboard.server') }}</label>
                        <select id="servers" class="form-select" aria-label="Default select example">
                           @foreach($servers as $server)
                                <option {{$server->id==$serverId || $server->id==session('serverId') ? 'selected' :''}} value="{{$server->id}}">{{$server->hostname}} ({{$server->address}})</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label>{{ __('admins.rconCommand') }}</label>  <input name="command" type="text" required class="form-control"/>
                        </div>
                        <div class="col-md-4">

                            <label>{{ __('admins.rconPassword') }}</label> <input type="password" name="password" required value="{{ !empty(\App\Models\SaServer::where('id', $serverId)->first()->rcon?->password) ? Illuminate\Support\Facades\Crypt::decrypt(\App\Models\SaServer::where('id', $serverId)->first()->rcon?->password): ''}}" class="form-control"/>
                        </div>
                    </div>
                    <div>
                        <center><button class="btn btn-success mt-3">{{ __('admins.rconExecuteCommand') }}</button></center>
                    </div>
                </form>
            </div>
            <!--  BEGIN CUSTOM SCRIPTS FILE  -->
            <x-slot:footerFiles>
                <script>
                   let baseUrl = '<?php echo env('APP_URL') ?>';
                    $('#servers').on('change', function() {
                        let selectedOption = $(this).val();
                        if (selectedOption) {
                            window.location.href= baseUrl+'/rcon/'+selectedOption;
                        }
                    });
                </script>
            </x-slot>
            <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>


