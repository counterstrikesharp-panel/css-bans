@php use App\Helpers\PermissionsHelper; @endphp
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        Admins - CSS-BANS
    </x-slot>
    @vite(['resources/scss/dark/assets/components/datatable.scss'])

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
                    <strong>Admins</strong>
                </h5>
            </div>
            <div class="card-body">
                @if(PermissionsHelper::isSuperAdmin())
                    <div class="mt-3 d-flex justify-content-end p-1">
                        <a href="{{env('VITE_SITE_DIR')}}/admin/create" class="col-md- btn btn-success">Add Admin</a>
                    </div>
                @endif
                    <div class="table-responsive display responsive nowrap">
                        <table class="table table-hover " id="adminsList" style="width:100%">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Player</th>
                            <th scope="col">Flags</th>
                            <th scope="col">Servers</th>
                            <th scope="col">Created</th>
                            <th scope="col">Ends</th>
                            @if(\App\Helpers\PermissionsHelper::isSuperAdmin())
                                <th scope="col">Actions</th>
                            @endif
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody >

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
        <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script>
            const adminListUrl = '{!! env('VITE_SITE_DIR') !!}/list/admins';
        </script>
    @vite(['resources/js/admin/admins.ts'])
    </x-slot>
</x-base-layout>


