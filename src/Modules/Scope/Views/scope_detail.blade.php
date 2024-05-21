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
                <tr>
            <th width='25%''>Slug</th><td>{{ $scope->slug }}</td></tr>
				<tr>
            <th width='25%''>Nama</th><td>{{ $scope->nama }}</td></tr>
				<tr>
            <th width='25%''>Akronim</th><td>{{ $scope->akronim }}</td></tr>
				<tr>
            <th width='25%''>Kode</th><td>{{ $scope->kode }}</td></tr>
				
            </table>
        </div>
    </div>
</div>
@endsection
