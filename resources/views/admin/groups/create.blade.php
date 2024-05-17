<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        Groups - CSS-BANS
    </x-slot>
    <x-slot:headerFiles>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot>
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Add New Group</h5>
                    <form action="{{ route('group.store') }}" method="POST">
                        @csrf
                        <div class="alert alert-gradient alert-dismissible fade show mb-4" role="alert">
                            <strong>Note:</strong> Adding permissions to an existing group for new servers will append the new permissions to the existing set, applying for all associated servers.
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label class="form-label" for="group_name">Group Name</label>
                            <input placeholder="Should Start with #" type="text" class="form-control" id="group_name" name="group_name" required/>
                        </div>

                        <div data-mdb-input-init class="form-outline mb-3">
                            <select multiple="multiple" class="form-select" id="server_ids" name="server_ids[]" required>
                                <option value="">Select Server</option>
                                <option value="all">All Servers</option>
                                @foreach($servers as $server)
                                    <option  value="{{ $server->id }}">{{ $server->hostname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr/>
                        <div class="mb-3">
                            <label>Permissions</label><br>
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}">
                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                        {{ $permission->description }} ({{ $permission->permission }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="immunity">Immunity</label>
                            <input type="number" id="immunity" name="immunity"  class="form-control" value="{{ old('immunity') }}" required>
                        </div>
                        <div class="mt-3">
                            <center> <button type="submit" class="btn btn-primary col-md-2 mx-auto ">Create Group</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <x-slot:footerFiles>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            @vite(['resources/js/groups/groups.ts'])
        </x-slot>
</x-base-layout>

