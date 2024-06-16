@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('admins.Groups') }} - CSS-BANS
    </x-slot>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>

    </x-slot>
    @if (session('success'))
        <x-alert type="success" :message="session('success')"/>
    @endif
    @if (session('error'))
        <x-alert type="danger" :message="session('error')"/>
    @endif
    <section class="mb-12">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Groups</strong>
                </h5>
            </div>
            <div class="card-body">
                @if(PermissionsHelper::isSuperAdmin() || @PermissionsHelper::hasGroupCreatePermission())
                    <div class="mt-3 d-flex justify-content-end p-1">
                        <a href="{{env('VITE_SITE_DIR')}}/group/create" class="col-md- btn btn-success">{{ __('admins.createGroup') }}</a>
                    </div>
                @endif
                <div class="table-responsive display responsive nowrap">
                    <table class="table table-hover " id="groupsList" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('admins.id') }}</th>
                            <th scope="col">{{ __('admins.group') }}</th>
                            <th scope="col">{{ __('admins.flags') }}</th>
                            <th scope="col">{{ __('admins.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
        <x-slot:footerFiles>
            <script>
                const groupsListUrl = '{!! env('VITE_SITE_DIR') !!}/list/groups';
            </script>
            @vite(['resources/js/groups/list.ts'])
        </x-slot>
</x-base-layout>


