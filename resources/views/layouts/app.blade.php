<!DOCTYPE html>
<html lang="en" data-mdb-theme="light">
<body>
<!-- Header -->
@include('partials.header')
<!-- Sidebar -->
@include('partials.nav')
<!--Main layout-->
<main style="margin-top: 58px">
    <div class="container pt-4">
        @yield('content')
    </div>
    <!-- Footer -->
    @include('partials.footer')
</main>

<!-- MDB -->
@vite(['resources/js/mdb.umd.min.js'])
<!-- Custom scripts -->
@include('partials.scripts')
<div id="loader">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
</body>
</html>
