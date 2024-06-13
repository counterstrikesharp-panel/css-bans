<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Server Visibility Settings') }}
        </x-slot>
        <x-slot:headerFiles>
            <style>
                .custom-switch {
                    display: inline-block;
                    width: 40px;
                    height: 20px;
                    position: relative;
                }
                .custom-switch input {
                    display: none;
                }
                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #ccc;
                    transition: .4s;
                    border-radius: 20px;
                }
                .slider:before {
                    position: absolute;
                    content: "";
                    height: 14px;
                    width: 14px;
                    left: 3px;
                    bottom: 3px;
                    background-color: white;
                    transition: .4s;
                    border-radius: 50%;
                }
                input:checked + .slider {
                    background-color: #28a745;
                }
                input:checked + .slider:before {
                    transform: translateX(20px);
                }
            </style>
        </x-slot:headerFiles>
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ __('Server Visibility Settings') }}</h1>
                <button id="syncServersButton" class="btn btn-secondary">{{ __('Sync New Servers') }}</button>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('settings.servers.update') }}" method="POST">
                @csrf
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>{{ __('Servers') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Hostname') }}</th>
                                    <th scope="col">{{ __('Address') }}</th>
                                    <th scope="col">{{ __('Visibility') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($servers as $server)
                                    <tr>
                                        <td>{{ $server->hostname }}</td>
                                        <td>{{ $server->address }}</td>
                                        <td>
                                            <input type="hidden" name="servers[{{ $server->id }}]" value="0"> <!-- Hidden input for unchecked boxes -->
                                            <label class="custom-switch">
                                                <input type="checkbox" id="server_{{ $server->id }}" name="servers[{{ $server->id }}]" value="1" {{ isset($serverVisibilitySettings[$server->id]) && $serverVisibilitySettings[$server->id] ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary">{{ __('settings.update') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <x-slot:footerFiles>
            <script>
                document.getElementById('syncServersButton').addEventListener('click', function() {
                    fetch('{{ getAppSubDirectoryPath() }}/settings/servers/sync', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            Snackbar.show({
                                text: '{{ __("settings.newServers") }}',
                                actionTextColor: '#fff',
                                backgroundColor: '#00ab55',
                                pos: 'top-center'
                            });
                        } else {
                            Snackbar.show({
                                text: '{{ __("settings.noNewServers") }}',
                                actionTextColor: '#fff',
                                backgroundColor: '#ff0000',
                                pos: 'top-center'
                            });
                        }
                    }).catch(error => {
                        alert('{{ __("settings.error") }}');
                    });
                });
            </script>
        </x-slot:footerFiles>
</x-base-layout>
