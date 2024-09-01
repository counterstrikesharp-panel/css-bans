<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Appeal Details') }}
        </x-slot>
        <x-slot:headerFiles>
            <!-- Add custom styles if any -->
        </x-slot:headerFiles>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Appeal Details') }}</div>

                        <div class="card-body">
                            <p><strong>{{ __('Ban Type') }}:</strong> {{ $appeal->ban_type }}</p>
                            <p><strong>{{ __('Identifier') }}:</strong> {{ $appeal->ban_type === 'IP' ? $appeal->ip : $appeal->steamid }}</p>
                            <p><strong>{{ __('Name') }}:</strong> {{ $appeal->name }}</p>
                            <p><strong>{{ __('admins.banReason') }}:</strong> {{ $appeal->reason }}</p>
                            <p><strong>{{ __('Email') }}:</strong> {{ $appeal->email }}</p>
                            <p><strong>{{ __('admins.status') }}:</strong> {{ $appeal->status }}</p>
                            <p><strong>{{ __('Created At') }}:</strong> {{ $appeal->created_at }}</p>

                            <form method="POST" action="{{ getAppSubDirectoryPath()}}/appeals/{{$appeal->id}}/status">
                                @csrf
                                @method('PUT')

                                <div class="mb-3 text-center">
                                    <button type="submit" name="status" value="APPROVED" class="btn btn-success">{{ __('Approve') }}</button>
                                    <button type="submit" name="status" value="REJECTED" class="btn btn-danger">{{ __('Reject') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-slot:footerFiles>
            <!-- Add custom scripts if any -->
        </x-slot:footerFiles>
</x-base-layout>
