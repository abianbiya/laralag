@extends('Laralag::layouts.master')
    @section('title')
        {{ $title }}
    @endsection
@section('content')
<x-Laralag::breadcrumb :title='"Detail Data $title"' :crumbs="['dashboard.index' => 'Dashboard', 'role.index' => 'Role']" />
<div class="row">
    <div class="col">
        <div class="table-responsive-md col-12">
            <table class="table table-hover table-nowrap mb-0">
                <div class='col-lg-2'><p>Slug</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $permission->slug }}</p></div>
                <div class='col-lg-2'><p>Nama</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $permission->nama }}</p></div>
                <div class='col-lg-2'><p>Action</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $permission->action }}</p></div>
									
            </table>
        </div>
    </div>
</div>
@endsection
