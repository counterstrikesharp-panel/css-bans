<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        Setup - CSS-BANS
        </x-slot>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
            <!--  BEGIN CUSTOM STYLE FILE  -->
            <style>
                .header-container {
                    display:none;
                }
                .sidebar-wrapper{
                    display: none;
                }
                #content {
                    margin-left: 0 !important
                }
            </style>
            <!--  END CUSTOM STYLE FILE  -->
            </x-slot>
            <!-- END GLOBAL MANDATORY STYLES -->

            <div class="row layout-top-spacing">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <h2 class="card-header">Panel Setup</h2>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="alert alert-danger  fade show" role="alert">
                                        <strong>NOTE:</strong> Use the same database host and database name used by cs2Simpleadmin. Panel uses the same database to configure tables
                                    </div>
                                    <form method="POST" action="{{env('VITE_SITE_DIR')}}/setup" onsubmit="showLoader()">
                                        @csrf
                                        <div class="form-group">
                                            <label for="appName">Panel Name:</label>
                                            <input type="text" name="APP_NAME" id="appName" class="form-control" value="{{ old('APP_NAME') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="appName">Panel URL:</label>
                                            <input placeholder="EX: https://yoursite.com (dont add / at end)" type="text" name="APP_URL" id="appURL" class="form-control" value="{{ old('APP_URL') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="dbHost">Database Host:</label>
                                            <input type="text" name="DB_HOST" id="dbHost" class="form-control" value="{{ old('DB_HOST') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="dbName">Database Name:</label>
                                            <input type="text" name="DB_DATABASE" id="dbName" class="form-control" value="{{ old('DB_DATABASE') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="dbUsername">Database Username:</label>
                                            <input type="text" name="DB_USERNAME" id="dbUsername" class="form-control" value="{{ old('DB_USERNAME') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="dbPassword">Database Password:</label>
                                            <input type="password" name="DB_PASSWORD" id="dbPassword" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="steamApiKey">Steam Web API Key:</label>
                                            <input type="text" name="STEAM_CLIENT_SECRET" id="steamApiKey" class="form-control" value="{{ old('STEAM_API_KEY') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="rconPassword">RCON PASSWORD:</label>
                                            <input type="password" name="RCON_PASSWORD" id="rconPassword" class="form-control" value="{{ old('RCON_PASSWORD') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="steamId64">Steam ID 64 (Panel Admin):</label>
                                            <input type="text" name="STEAM_ID_64" id="steamId64" class="form-control" value="{{ old('STEAM_ID_64') }}">
                                        </div>
                                        <input type="hidden" name="STEAM_REDIRECT_URI" value="${APP_URL}/auth/steam/callback" />
                                        <div style="text-align: center;">
                                            <button type="submit" class="btn btn-primary mt-3">Setup</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bootstrap Loader -->
                <div class="loader-container text-center" id="loader" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <script>
                    function showLoader() {
                        document.getElementById('loader').style.display = 'block';
                    }
                </script>

            </div>

            <!--  BEGIN CUSTOM SCRIPTS FILE  -->
            <x-slot:footerFiles>

                </x-slot>
                <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>



