<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
    {{ __('admins.settings') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    </x-slot:headerFiles>
    <div class="container mt-5">
        <h1>{{ __('admins.settings') }}</h1>
        <div class="alert alert-warning" role="alert">
            <strong>{{ __('Warning') }}!</strong> {{ __('settings.beCareful') }}
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
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
                                            <option value="Enabled" {{ $value == 'Enabled' ? 'selected' : '' }}>{{ __('Enabled') }}</option>
                                            <option value="Disabled" {{ $value == 'Disabled' ? 'selected' : '' }}>{{ __('Disabled') }}</option>
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
            <button type="submit" class="btn btn-primary">{{ __('settings.update') }}</button>
        </form>

        <h2 class="mt-5">{{ __('Test SMTP Settings') }}</h2>
        <form action="{{getAppSubDirectoryPath()}}/settings/test-email" method="POST">
            @csrf
            <div class="mb-3">
                <label for="test_email" class="form-label">{{ __('settings.testEmailAddress') }}</label>
                <input type="email" class="form-control" id="test_email" name="test_email" required>
            </div>
            <button type="submit" class="btn btn-secondary">{{ __('settings.sendTestEmail') }}</button>
        </form>
    </div>
    <x-slot:footerFiles>

    </x-slot:footerFiles>
</x-base-layout>
