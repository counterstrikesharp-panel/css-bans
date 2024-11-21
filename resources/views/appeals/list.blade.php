<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Appeals List') }}
        </x-slot>
        <x-slot:headerFiles>
        </x-slot:headerFiles>

        <div class="container mt-5">
            <div class="card">
                <div class="card-header">{{ __('Appeals List') }}</div>
                <div class="card-body" style="max-height: 500px;overflow: auto">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ __('admins.id') }}</th>
                            <th>{{ __('Ban Type') }}</th>
                            <th>{{ __('SteamID/IP') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('admins.banReason') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('admins.status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('admins.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($appeals as $appeal)
                            <tr>
                                <td>{{ $appeal->id }}</td>
                                <td>{{ $appeal->ban_type }}</td>
                                <td>{{ $appeal->ban_type == 'Steam ID' ? $appeal->steamid : $appeal->ip }}</td>
                                <td>{{ $appeal->name }}</td>
                                <td>
                                    <div style="width: 200px; overflow: hidden;">
                                        <textarea rows="2" readonly style="width: 100%; border: none; resize: none; background-color: transparent; color: inherit; caret-color: transparent;">{{ $appeal->reason }}</textarea>
                                    </div>
                                </td>
                                <td>{{ $appeal->email }}</td>
                                <td>
                                    <span class="badge {{ $appeal->status == 'PENDING' ? 'badge-warning' : ($appeal->status == 'APPROVED' ? 'badge-success' : 'badge-danger') }}">
                                        {{ $appeal->status }}
                                    </span>
                                </td>
                                <td>{{ $appeal->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ getAppSubDirectoryPath() }}/appeals/{{$appeal->id}}" class="btn btn-primary btn-sm">{{ __('View') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-slot:footerFiles>
            </x-slot>
</x-base-layout>
