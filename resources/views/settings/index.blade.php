<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Settings') }}
        </x-slot>
        <x-slot:headerFiles>

        </x-slot:headerFiles>
        <div class="container mt-5">
            <h1>{{ __('Settings') }}</h1>
            <div class="alert alert-warning" role="alert">
                <strong>Warning!</strong> Be careful when making changes here. Incorrect settings can cause issues with the application.
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @foreach($settings as $category => $group)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2>{{ $category }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($group as $key => $value)
                                    <div class="col-md-6 mb-3">
                                        <label for="{{ $key }}" class="form-label">{{ $key }}</label>
                                        @if(in_array($key, ['RANKS', 'VIP', 'SKINS']))
                                            <select class="form-select" id="{{ $key }}" name="{{ $key }}">
                                                <option value="Enabled" {{ $value == 'Enabled' ? 'selected' : '' }}>Enabled</option>
                                                <option value="Disabled" {{ $value == 'Disabled' ? 'selected' : '' }}>Disabled</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $value) }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </form>
        </div>
        <x-slot:footerFiles>

            </x-slot>
</x-base-layout>
