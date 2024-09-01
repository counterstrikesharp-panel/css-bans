<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>{{ __('Add Server Setting') }}</x-slot:pageTitle>
    <x-slot:headerFiles></x-slot:headerFiles>

    <div class="container mt-5 col-md-8">
        <h1>{{ __('Add Server Setting') }}</h1>
        <blockquote class="blockquote">
            {{ __('This feature introduces multi-server support for the Ranks module. It allows administrators to dynamically switch between different server databases for displaying player ranks. This is especially useful for managing multiple game servers from a single interface.') }}
        </blockquote>
        <form action="{{ route('module-server-settings.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="module_name">{{ __('Module Name') }}</label>
                <input value="Ranks" readonly type="text" class="form-control" id="module_name" name="module_name" required>
            </div>
            <div class="form-group mb-3">
                <label for="name">{{ __('admins.serverName') }}</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_host">{{ __('DB Host') }}</label>
                <input type="text" class="form-control" id="db_host" name="db_host" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_user">{{ __('DB User') }}</label>
                <input type="text" class="form-control" id="db_user" name="db_user" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_pass">{{ __('DB Pass') }}</label>
                <input type="text" class="form-control" id="db_pass" name="db_pass" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_name">{{ __('DB Name') }}</label>
                <input type="text" class="form-control" id="db_name" name="db_name" required>
            </div>
            <div class="form-group mb-3">
                <label for="active">{{ __('dashboard.active') }}</label>
                <select class="form-control" id="active" name="active" required>
                    <option value="1">{{ __('Yes') }}</option>
                    <option value="0">{{ __('No') }}</option>
                </select>
            </div>
            <center><button type="submit" class="btn btn-primary justify-content-center">{{ __('Add') }}</button></center>
        </form>
    </div>

    <x-slot:footerFiles></x-slot:footerFiles>
</x-base-layout>
