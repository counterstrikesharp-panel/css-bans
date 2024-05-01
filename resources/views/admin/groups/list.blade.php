@php use App\Helpers\PermissionsHelper; @endphp
@extends('layouts.app')

@section('content')
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
                @if(PermissionsHelper::isSuperAdmin())
                    <div class="mt-3 d-flex justify-content-end p-1">
                        <a href="{{env('VITE_SITE_DIR')}}/group/create" class="col-md- btn btn-success">Create Group</a>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover " id="groupsList">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Group</th>
                            <th scope="col">Flags</th>
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
@vite(['resources/js/groups/list.ts'])

