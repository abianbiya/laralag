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
                    <th width='25%''>User</th>
<td>{{ $log->user->id }}</td>
</tr>
				<tr>
                    <th width='25%''>Name</th>
<td>{{ $log->name }}</td>
</tr>
				<tr>
                    <th width='25%''>Aktivitas</th>
<td>{{ $log->aktivitas }}</td>
</tr>
				<tr>
                    <th width='25%''>Route</th>
<td>{{ $log->route }}</td>
</tr>
				<tr>
                    <th width='25%''>Action</th>
<td>{{ $log->action }}</td>
</tr>
				<tr>
                    <th width='25%''>Context</th>
<td>{{ $log->context }}</td>
</tr>
				<tr>
                    <th width='25%''>Data From</th>
<td>{{ $log->data_from }}</td>
</tr>
				<tr>
                    <th width='25%''>Data To</th>
<td>{{ $log->data_to }}</td>
</tr>
				<tr>
                    <th width='25%''>Ip Address</th>
<td>{{ $log->ip_address }}</td>
</tr>
				<tr>
                    <th width='25%''>User Agent</th>
<td>{{ $log->user_agent }}</td>
</tr>
				
            </table>
        </div>
    </div>
</div>
@endsection
