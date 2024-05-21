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
                    <th width='25%''>Role</th>
<td>{{ $roleuser->role->id }}</td>
</tr>
				<tr>
                    <th width='25%''>User</th>
<td>{{ $roleuser->user->id }}</td>
</tr>
				<tr>
                    <th width='25%''>Scope</th>
<td>{{ $roleuser->scope->id }}</td>
</tr>
				
            </table>
        </div>
    </div>
</div>
@endsection
