@php use App\Helpers\PermissionsHelper; @endphp
@extends('layouts.app')

@section('content')
    <section class="mb-12">
        <div class="card">
            @if(PermissionsHelper::hasMutePermission())
                <div class="mt-3 d-flex justify-content-end p-1">
                    <a href="{{env('VITE_SITE_DIR')}}/mute/add" class="col-md- btn btn-success">Add Mute</a>
                </div>
            @endif
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Mutes</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover " id="mutesList">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Player</th>
                            <th scope="col">Muted By</th>
                            <th scope="col">Admin Steam</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Ends</th>
                            <th scope="col">Muted</th>
                            <th scope="col">Server</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            <th scope="col">Progress</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@vite(['resources/js/mutes/mutes.ts'])

