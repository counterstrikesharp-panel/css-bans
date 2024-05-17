<x-base-layout :scrollspy="false">
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
    <x-slot:pageTitle>
        Logs - CSS-BANS
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
            <div class="row layout-top-spacing rcon-container">
                <div class="alert alert-light-success alert-dismissible fade show mb-4" role="alert">
                    <strong>Note:</strong> Rcon password will be saved automatically on first successful command execution
                </div>
                <pre id="rcon_output">
                    {{session('data')}}
                </pre>
                <form class="form-group" action="{{ route('rcon.execute', ['server_id' => $serverId ?? $servers->first()->id]) }}" method="POST">
                    @csrf
                    <div class="col-md-12">
                        <label>Server</label>
                        <select id="servers" class="form-select" aria-label="Default select example">
                           @foreach($servers as $server)
                                <option selected="{{($server->id==$serverId) ? 'selected' :''}}" value="{{$server->id}}">{{$server->hostname}} ({{$server->address}})</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label>Rcon Command</label>  <input name="command" type="text" required class="form-control"/>
                        </div>
                        <div class="col-md-4">

                            <label>Password</label> <input type="password" name="password" required value="{{ !empty($server->rcon?->password) ? Illuminate\Support\Facades\Crypt::decrypt($server->rcon?->password): ''}}" class="form-control"/>
                        </div>
                    </div>
                    <div>
                        <center><button class="btn btn-success mt-3">Execute Command</button></center>
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


