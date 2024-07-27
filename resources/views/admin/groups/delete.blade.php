<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('admins.Groups') }} - CSS-BANS
    </x-slot>
    <x-slot:headerFiles>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot>
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
                <div class="card-header">
                    <h5 class="card-title text-center mb-4">{{ __('admins.deleteGroup') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('group.delete', ['id' => $groupDetails->id]) }}" method="POST">
                        @csrf
                        <div class="alert-info alert note note-info mb-3">
                            <strong>{{ __('admins.note') }}</strong> {{ __('admins.noteGroupMessage') }}
                        </div>
                        <div class="mb-3">
                            <label><b>{{ __('admins.groupName') }}</label>
                            <input class="form-control" value="{{$groupDetails->name}}" readonly>
                        </div>
                        <!-- Servers Multi-Select Dropdown -->
                        <div class="mb-3">
                            <select multiple="multiple"  class="form-control" id="server_ids" name="server_ids[]">
                                <option value="all">{{ __('admins.allServers') }}</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}">
                                        {{ $server->hostname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
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
