<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>{{ __('Add Server Setting') }}</x-slot:pageTitle>
    <x-slot:headerFiles></x-slot:headerFiles>

    <div class="container mt-5 col-md-8">
        <h1>{{ __('Add Server Setting') }}</h1>
        <blockquote class="blockquote">
            This feature introduces multi-server support for the Ranks module. It allows administrators to dynamically switch between different server databases for displaying player ranks. This is especially useful for managing multiple game servers from a single interface.
        </blockquote>
        <form action="{{ route('module-server-settings.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="module_name">Module Name</label>
                <input value="Ranks" readonly type="text" class="form-control" id="module_name" name="module_name" required>
            </div>
            <div class="form-group mb-3">
                <label for="name">Server Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_host">DB Host</label>
                <input type="text" class="form-control" id="db_host" name="db_host" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_user">DB User</label>
                <input type="text" class="form-control" id="db_user" name="db_user" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_pass">DB Pass</label>
                <input type="text" class="form-control" id="db_pass" name="db_pass" required>
            </div>
            <div class="form-group mb-3">
                <label for="db_name">DB Name</label>
                <input type="text" class="form-control" id="db_name" name="db_name" required>
            </div>
            <div class="form-group mb-3">
                <label for="active">Active</label>
                <select class="form-control" id="active" name="active" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <center><button type="submit" class="btn btn-primary justify-content-center">Add</button></center>
        </form>
    </div>

    <x-slot:footerFiles></x-slot:footerFiles>
</x-base-layout>
