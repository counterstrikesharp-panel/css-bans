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
@vite(['resources/js/app.js'])
<!-- MDB -->
@vite(['resources/js/mdb.umd.min.js'])
<!-- Custom scripts -->
@include('partials.scripts')
<x-loader />
</body>
</html>
