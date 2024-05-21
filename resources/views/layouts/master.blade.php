<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  data-layout="vertical" data-topbar="light"  data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | {{ config('app.name') }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('Laralag::layouts.head-css')
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

    @include('Laralag::layouts.customizer')
    <!-- JAVASCRIPT -->
    @include('Laralag::layouts.vendor-scripts')
    @livewireScripts
</body>

</html>
