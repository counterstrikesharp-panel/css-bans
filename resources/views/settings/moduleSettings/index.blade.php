<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>{{ __('Module Server Settings') }}</x-slot:pageTitle>
    <x-slot:headerFiles></x-slot:headerFiles>

    <div class="container mt-5">
        <h1>{{ __('Module Server Settings') }}</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <blockquote class="blockquote">
            {{ __('This feature introduces multi-server support for the Ranks module. It allows administrators to dynamically switch between different server databases for displaying player ranks. This is especially useful for managing multiple game servers from a single interface.') }}
        </blockquote>
        <a href="{{ route('module-server-settings.create') }}" class="btn btn-primary mb-3">{{ __('Add New Server Setting') }}</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Module Name') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('DB Host') }}</th>
                    <th>{{ __('DB User') }}</th>
                    <th>{{ __('DB Pass') }}</th>
                    <th>{{ __('DB Name') }}</th>
                    <th>{{ __('dashboard.active') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td>{{ $setting->module_name }}</td>
                        <td>{{ $setting->name }}</td>
                        <td>{{ $setting->db_host }}</td>
                        <td>{{ $setting->db_user }}</td>
                        <td>{{ $setting->db_pass }}</td>
                        <td>{{ $setting->db_name }}</td>
                        <td>{{ $setting->active ? __('Yes') : __('No') }}</td>
                        <td>
                            <a href="{{ route('module-server-settings.edit', $setting->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                            <form action="{{ route('module-server-settings.destroy', $setting->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-slot:footerFiles></x-slot:footerFiles>
</x-base-layout>
