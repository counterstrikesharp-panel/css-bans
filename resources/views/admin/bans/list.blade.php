@php use App\Helpers\PermissionsHelper; @endphp
@extends('layouts.app')

@section('content')
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
    <section class="mb-12">
        <div class="card">
            @if(PermissionsHelper::hasBanPermission())
                <div class="mt-3 d-flex justify-content-end p-1">
                    <a href="{{env('VITE_SITE_DIR')}}/ban/add" class="col-md- btn btn-success">Add Ban</a>
                </div>
            @endif
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Bans</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover " id="bansList">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Player</th>
                            <th scope="col">Banned By</th>
                            <th scope="col">Admin Steam</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Ends</th>
                            <th scope="col">Banned</th>
                            <th scope="col">Server</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            <th scope="col">Progress</th>
                        </tr>
                        </thead>
                        <tbody id="serverList">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@vite(['resources/js/bans/bans.ts'])

