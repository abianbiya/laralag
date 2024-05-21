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
                        <th width="25%">Slug</th>
                        <td>{{ $role->slug }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $role->nama }}</td>
                    </tr>
                    <tr>
                        <th>Tags</th>
                        <td>{{ $role->tags }}</td>
                    </tr>
                    <tr>
                        <th>Permissions</th>
                        <td>

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row" x-data="{ search: '' }">
        <div class="col-12">

            <form action="{{ route('role.permission.update', $role->id) }}" method="post">
                @csrf
                <input type="hidden" name="role_id" value="{{ $role->id }}">
                <div class="col-12 mb-3">
                    <input type="text" class="form-control" placeholder="Search..." x-model="search">
                </div>
                <div class="row row-cols-1 row-cols-md-2 g-4 mt-4">
                @foreach ($permissions as $key => $permission)
                    <div class="col mt-0" x-show="search === '' || '{{ strtolower($key) }}'.includes(search.toLowerCase())">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ str($key)->title() }} <button type="button" class="btn btn-sm btn-secondary py-0 px-1" onclick="checkAllInCard(this)">All</button></h4>
                            
                            @foreach ($permission as $item)
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="permissions[{{ $item->id }}]" id="{{ $item->id }}" {{ $role->permission->contains($item->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $item->id }}"><code>[{{ $item->slug }}]</code>  {{ $item->nama }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                @endforeach
                </div>
                <button type="submit" class="btn btn-primary my-2">Simpan</button>
            </form>
        </div>
    </div>
    <script>
        function checkAllInCard(button) {
            // Get the card body that contains the button
            const cardBody = button.closest('.card-body');
            // Get all checkboxes within this card body
            const checkboxes = cardBody.querySelectorAll('input[type="checkbox"]');
            // Check all checkboxes
            checkboxes.forEach(checkbox => checkbox.checked = !checkbox.checked);
        }
    </script>
@endsection
