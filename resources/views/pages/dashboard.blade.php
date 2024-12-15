@extends('Laralag::layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('content')
    @component('Laralag::components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Dashboard
        @endslot
    @endcomponent
    @if(filled(config('laralag.include_dashboard_blade', null)))
        @include(config('laralag.dashboard_blade'))
    @endif
@endsection
@section('script')
    <script src="{{ URL::asset('lag/libs/tom-select/js/tom-select.base.min.js') }}"></script>
    <script src="{{ URL::asset('lag/js/app.js') }}"></script>
@endsection
