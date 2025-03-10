<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Hybrix Laravel Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
     <meta name="keywords" content="hybrix,hybrix laravel,admin,dashboard,vite,livewire,livewire admin,laravel vite">
     
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('lag/images/favicon.ico') }}">

    <!-- include head css -->
    @include('Laralag::layouts.head-css')

    @livewireStyles
</head>

<body>

    @yield('content')

    <!-- vendor-scripts -->
    @include('Laralag::layouts.vendor-scripts')

    @livewireScripts
</body>

</html>

