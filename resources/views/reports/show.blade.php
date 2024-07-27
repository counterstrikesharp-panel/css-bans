<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Report Details') }}
        </x-slot>
        <x-slot:headerFiles>
        </x-slot:headerFiles>

        <div class="container mt-5">
            <div class="card">
                <div class="card-header">{{ __('Report Details') }}</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>{{ __('Ban Type') }}</th>
                            <td>{{ $report->ban_type }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('SteamID/IP') }}</th>
                            <td>{{ $report->ban_type == 'Steam ID' ? $report->steamid : $report->ip }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Nickname') }}</th>
                            <td>{{ $report->nickname }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Comments') }}</th>
                            <td>{{ $report->comments }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <td>{{ $report->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Server') }}</th>
                            <td>{{ $report->server->hostname }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Media Link') }}</th>
                            <td><a href="{{ $report->media_link }}" target="_blank">{{ __('View Proof') }}</a></td>
                        </tr>
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>

                    <form method="POST" action="{{ getAppSubDirectoryPath() }}/reports/destroy/{{$report->id}}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger mt-3">{{ __('Delete Report') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <x-slot:footerFiles>
            </x-slot>
</x-base-layout>
