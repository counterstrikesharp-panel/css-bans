@php use App\Helpers\PermissionsHelper; @endphp

<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        Demos List
    </x-slot>

    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{asset('plugins/apex/apexcharts.css')}}">
        @vite(['resources/scss/light/assets/components/modal.scss'])
        @vite(['resources/scss/dark/assets/components/modal.scss'])
        @vite(['resources/scss/dark/plugins/apex/custom-apexcharts.scss'])
        <style>
            .apexcharts-gridline {
                stroke: transparent !important;
            }
            .apexcharts-legend-text {
                display: none;
            }
            .interval-container {
                padding: 19px;
            }
            .chart-section {
                background: #191e3a;
            }
        </style>
    </x-slot>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <!-- <h5>Demos List</h5> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Filter Section -->
                    <div class="mb-4">
                        <form method="GET" action="" class="row g-3">
                            <div class="col-md-3">
                                <select name="server" class="form-select">
                                    <option value="">All Servers</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="map" class="form-select">
                                    <option value="">All Maps</option>
                                    @foreach($maps as $map)
                                        <option value="{{ $map }}" {{ request('map') == $map ? 'selected' : '' }}>{{ $map }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Server</th>
                                <th>Map</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demos as $demo)
                                <tr>
                                    <td>{{ $demo->file }}</td>
                                    <td>{{ $demo->server_name }}</td>
                                    <td>{{ $demo->map }}</td>
                                    <td>{{ \Carbon\Carbon::parse($demo->date)->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ env('DEMO_BASE_URL') }}/download/{{ $demo->file }}.zip" 
                                            class="btn btn-success btn-sm">
                                                Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot:footerFiles>
        <x-modal :title="''" :body="''"/>
        @vite(['resources/js/dashboard/recent.ts'])
        @vite(['resources/js/dashboard/servers.ts'])
    </x-slot>
</x-base-layout>