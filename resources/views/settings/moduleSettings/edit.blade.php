<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>{{ __('Edit Server Setting') }}</x-slot:pageTitle>
    <x-slot:headerFiles></x-slot:headerFiles>

    <div class="container mt-5">
        <h1>{{ __('Edit Server Setting') }}</h1>
        <form action="{{ route('module-server-settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="module_name">{{ __('Module Name') }}</label>
                <input readonly type="text" class="form-control" id="module_name" name="module_name" value="Ranks" required>
            </div>
            <div class="form-group mb-3">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $setting->name }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_host">{{ __('DB Host') }}</label>
                <input type="text" class="form-control" id="db_host" name="db_host" value="{{ $setting->db_host }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_user">{{ __('DB User') }}</label>
                <input type="text" class="form-control" id="db_user" name="db_user" value="{{ $setting->db_user }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_pass">{{ __('DB Pass') }}</label>
                <input type="password" class="form-control" id="db_pass"  value="{{ $setting->db_pass }}" name="db_pass">
            </div>
            <div class="form-group mb-3">
                <label for="db_name">{{ __('DB Name') }}</label>
                <input type="text" class="form-control" id="db_name" name="db_name" value="{{ $setting->db_name }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="active">{{ __('dashboard.active') }}</label>
                <select class="form-control" id="active" name="active" required>
                    <option value="1" {{ $setting->active ? 'selected' : '' }}>{{ __('Yes') }}</option>
                    <option value="0" {{ !$setting->active ? 'selected' : '' }}>{{ __('No') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </form>
    </div>

    <x-slot:footerFiles></x-slot:footerFiles>
</x-base-layout>
