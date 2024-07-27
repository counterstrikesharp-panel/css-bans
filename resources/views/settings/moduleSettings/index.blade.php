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
            This feature introduces multi-server support for the Ranks module. It allows administrators to dynamically switch between different server databases for displaying player ranks. This is especially useful for managing multiple game servers from a single interface.
        </blockquote>
        <a href="{{ route('module-server-settings.create') }}" class="btn btn-primary mb-3">Add New Server Setting</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Module Name</th>
                    <th>Name</th>
                    <th>DB Host</th>
                    <th>DB User</th>
                    <th>DB Pass</th>
                    <th>DB Name</th>
                    <th>Active</th>
                    <th>Actions</th>
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
                        <td>{{ $setting->active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('module-server-settings.edit', $setting->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('module-server-settings.destroy', $setting->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-slot:footerFiles></x-slot:footerFiles>
</x-base-layout>
