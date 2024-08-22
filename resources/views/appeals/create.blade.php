<!-- resources/views/appeals/create.blade.php -->
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Appeal a Ban') }}
        </x-slot>
        <x-slot:headerFiles>
            <!-- Add this if you are using any custom styles or scripts -->
        </x-slot:headerFiles>
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
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Appeal a Ban') }}</div>

                        <div class="card-body">
                            <blockquote class="blockquote">
                                {{ __('appeal.blockquote') }}
                            </blockquote>
                            <form method="POST" action="{{ getAppSubDirectoryPath()}}/appeals">
                                @csrf

                                <div class="mb-3">
                                    <label for="ban_type" class="form-label">{{ __('Ban Type') }}</label>
                                    <select id="ban_type" class="form-select" name="ban_type" required onchange="toggleBanInput()">
                                        <option value="Steam ID">{{ __('admins.steam') }}</option>
                                        <option value="IP">IP</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="ban-input-container">
                                    <label for="ban_input" class="form-label">{{ __('Your SteamID') }}</label>
                                    <input id="ban_input" type="text" class="form-control @error('ban_input') is-invalid @enderror" name="ban_input" value="{{ old('ban_input') }}" required autofocus>
                                    @error('ban_input')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reason" class="form-label">{{ __('Reason why you should be unbanned') }}</label>
                                    <textarea id="reason" class="form-control @error('reason') is-invalid @enderror" name="reason" rows="5" required>{{ old('reason') }}</textarea>
                                    @error('reason')
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

                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer text-center">
                            <p>{{ __('What happens after I post my appeal?') }}</p>
                            <p>{{ __('The staff team will be notified of your appeal. They will then review if the ban is conclusive. After reviewing you will get a reply, which usually means within 24 hours.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-slot:footerFiles>
            <script>
                function toggleBanInput() {
                    const banType = document.getElementById('ban_type').value;
                    const banInputLabel = document.querySelector('#ban-input-container label');
                    const banInput = document.getElementById('ban_input');

                    if (banType === 'IP') {
                        banInputLabel.textContent = 'Your IP';
                        banInput.name = 'ip';
                    } else {
                        banInputLabel.textContent = '{{ __('Your SteamID') }}';
                        banInput.name = 'steamid';
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    toggleBanInput(); // Initialize on page load
                });
            </script>
            </x-slot>
</x-base-layout>
