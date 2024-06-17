<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Submit a Report') }}
        </x-slot>
        <x-slot:headerFiles>
        </x-slot:headerFiles>
        @if (session('success'))
            <x-alert type="success" :message="session('success')"/>
        @endif
        @if (session('error'))
            <x-alert type="danger" :message="session('error')"/>
        @endif
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Submit a Report') }}</div>

                        <div class="card-body">
                            <p>
                                {{ __('reports.textOne') }}
                            </p>
                            <p>
                                {{ __('reports.textTwo') }}
                            </p>
                            <form method="POST" action="{{getAppSubDirectoryPath()}}/reports/store">
                                @csrf

                                <div class="mb-3">
                                    <label for="ban_type" class="form-label">{{ __('Ban Type') }}</label>
                                    <select id="ban_type" class="form-select" name="ban_type" required>
                                        <option value="Steam ID">SteamID</option>
                                        <option value="IP">IP</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="steamid" class="form-label">{{ __('Players SteamID') }}</label>
                                    <input id="steamid" type="text" class="form-control @error('steamid') is-invalid @enderror" name="steamid" value="{{ old('steamid') }}" required autofocus>
                                    @error('steamid')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="ip" class="form-label">{{ __('Players IP') }}</label>
                                    <input id="ip" type="text" class="form-control @error('ip') is-invalid @enderror" name="ip" value="{{ old('ip') }}">
                                    @error('ip')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nickname" class="form-label">{{ __('Players Nickname') }}</label>
                                    <input id="nickname" type="text" class="form-control @error('nickname') is-invalid @enderror" name="nickname" value="{{ old('nickname') }}" required>
                                    @error('nickname')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="comments" class="form-label">{{ __('Comments') }}</label>
                                    <textarea id="comments" class="form-control @error('comments') is-invalid @enderror" name="comments" rows="5" required>{{ old('comments') }}</textarea>
                                    @error('comments')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Your Name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Your Email') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="server" class="form-label">{{ __('dashboard.server') }}</label>
                                    <select id="server" class="form-select" name="server_id" required>
                                        <option value="">{{ __('-- Select Server --') }}</option>
                                        @foreach ($servers as $server)
                                            <option value="{{ $server->id }}">{{ $server->hostname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="media_link" class="form-label">{{ __('Media Link (Proof)') }}</label>
                                    <input id="media_link" type="url" class="form-control @error('media_link') is-invalid @enderror" name="media_link" value="{{ old('media_link') }}">
                                    @error('media_link')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer text-center">
                            <p>{{ __('reports.textThree') }}</p>
                            <p>{{ __('reports.textFour') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-slot:footerFiles>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const banTypeSelect = document.getElementById('ban_type');
                    const steamidField = document.getElementById('steamid');
                    const ipField = document.getElementById('ip');

                    banTypeSelect.addEventListener('change', function() {
                        if (this.value === 'Steam ID') {
                            steamidField.closest('.mb-3').style.display = 'block';
                            ipField.closest('.mb-3').style.display = 'none';
                        } else {
                            steamidField.closest('.mb-3').style.display = 'none';
                            ipField.closest('.mb-3').style.display = 'block';
                        }
                    });

                    banTypeSelect.dispatchEvent(new Event('change'));
                });
            </script>
            </x-slot>
</x-base-layout>
