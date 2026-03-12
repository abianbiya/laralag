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
                    <th width='25%''>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>
				<tr>
                    <th width='25%''>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
				<tr>
                    <th width='25%''>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th width='25%''>Identitas</th>
                    <td>{{ $user->identitas }}</td>
                </tr>
                <tr>
                    <th width='25%''>Level Akses</th>
                <td>    
                        @if(count($user->roles) > 0)
                            <ul class="list-group">
                                @foreach($user->roleUser as $role)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $role->role->nama }} {{ $role->scope_id ? "(".$role->scope->nama.")" : '' }}
                                        <form action="{{ route('roleuser.destroy', [$role->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin?')"> <i class="bi bi-trash"></i> </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-danger">Belum mempunyai level akses.</span>
                        @endif
                        
                        <br>
                        <a href="{{ route('roleuser.create', ['user_id' => $user->id, 'back' => 'user.index']) }}" class="btn btn-sm btn-primary">Tambah Role</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
