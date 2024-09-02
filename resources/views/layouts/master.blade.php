<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  data-layout="vertical" data-topbar="light"  data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | {{ config('app.name') }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Laralag CRUD Generator and Access Management" name="description" />
    <meta content="Abianbiya" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('Laralag::layouts.head-css')
    @stack('css')
    @livewireStyles
</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('Laralag::layouts.topbar')
        @include('Laralag::layouts.top-tagbar')
        {{-- @include('components.sidebar') --}}
        <x-laralag-sidebar/>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('Laralag::layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @if(config('laralag.theme_customizer_enabled'))
        @include('Laralag::layouts.customizer')
    @endif
    <!-- JAVASCRIPT -->
    @include('Laralag::layouts.vendor-scripts')
    @stack('js')
    @livewireScripts
</body>

</html>
