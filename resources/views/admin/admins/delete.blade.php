<x-base-layout :scrollspy="false">
<x-slot:pageTitle>
    {{ __('admins.title') }} - CSS-BANS
    </x-slot>
    <x-slot:headerFiles>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot>
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title text-center mb-4">{{ __('admins.delete') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.delete', ['player_steam' => $admin->player_steamid]) }}" method="POST">
                        @csrf

                        <!-- Servers Multi-Select Dropdown -->
                        <div class="mb-3">
                            <select multiple="multiple"  class="form-control" id="server_ids" name="server_ids[]">
                            <option value="all">{{__('admins.allServers')}}</option>
                            @foreach($servers as $server)
                                    <option value="{{ $server->id }}" {{ in_array($server->id, old('server_ids', [])) ? 'selected' : '' }}>
                                        {{ $server->hostname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/js/admin/delete.ts'])
    </x-slot>
</x-base-layout>


