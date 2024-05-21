@extends('Laralag::layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />
<div class="row">
    <div class="col-9">
        <form action="{{ route('menu.index') }}" method="get">
            <div class="form-group col-md-3 form-icon right position-relative">
                <input type="text" class="form-control form-control-sm form-control-icon" value="{{ request()->get('search') }}" name="search" placeholder="Search">
                <i class="bi bi-search"></i>
            </div>
        </form>
    </div>
    <div class="col-3">
        {{ customButton('menugroup.create', title: 'Tambah Menu Group', icon: "bi bi-plus-lg", class: "btn-outline-primary float-end", routeParams: ['back' => 'menu.index']) }}
        {{-- {{ actionButton('menu.create', title: $title) }} --}}
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        @include('Laralag::include.flash')
    </div>
</div>
<div class="table-responsive-md col-12">
    <!-- Hoverable Rows -->
    <table class="table table-hover table-sm table-nowrap mb-0">
        <thead>
            <tr>
                <th scope="col" class="text-center">No</th>
			    <th scope="col">Nama</th>
			    {{-- <th scope="col">Nama (English)</th> --}}
			    <th scope="col">Icon</th>
			    <th scope="col">Urutan</th>
			    <th scope="col">Tampilkan</th>
			    <th scope="col">Opsi</th>
            </tr>
        <tbody>
            @foreach ($menuGroups as $menuGroup)
                <tr>
                    <td colspan="5" class="table-secondary px-3 pt-2">
                        {{ $menuGroup->nama }} 
                    </td>
                    <td class="table-secondary">
                        {{ customButton('menu.create', tooltip: 'Tambah Menu di '.$menuGroup->nama, routeParams: ['menuGroup' => $menuGroup->id], icon: "bi bi-plus-lg") }}
                        {{ customButton('menugroup.edit', $menuGroup->id, tooltip: "Edit Group Menu", icon: "bi bi-pencil", class: 'btn-outline-warning') }}
                        {{ customButton('menugroup.destroy', $menuGroup->id, tooltip: "Hapus Group Menu", icon: "bi bi-trash", class: 'btn-outline-danger') }}
                    </td>
                </tr>
                @forelse ($menuGroup->menu->sortBy('urutan') as $key => $item)
                <tr>
                    <td class="text-center">{{ ($key+1) }}</td>
                    <td>{{ $item->nama }}</td>
                    {{-- <td>{{ $item->nama_en }}</td> --}}
                    <td><i class="{{ $item->icon }}"></i> {{ $item->icon }}</td>
                    <td>{{ $item->urutan }}</td>
                    <td>{{ $item->is_tampil ? 'Tampil' : 'Sembunyi' }}</td>
                    <td>
                        {{ customButton('module.create', ['menu_id' => $item->id, 'back' => 'menu.index'], tooltip: 'Tambah Module', icon: "bi bi-plus-lg") }}
                        {{ actionButton('menu.edit', $item->id, $title) }}
                        {{ actionButton('menu.destroy', $item->id, $title) }}

                        {{-- untuk custom button dapat menggunakan fungsi di bawah atau menggunakan fungsi laravel-html  --}}
                        {{-- {{ customButton('menu.blabla', $item->id, $title, 'bi bi-info-square', 'btn-outline-primary') }} --}}
                    </td>
                </tr>
                @foreach ($item->module->sortBy('urutan') as $module)
                    <tr>
                        <td class="text-center"></td>
                        <td colspan="4"> <i class="bi bi-arrow-return-right"></i> {{ $module->nama }} ({{ $module->routing }})  
                            {!! $module->is_tampil ? '<i class="bi bi-eye text-success"></i>' : '<i class="bi bi-eye-slash text-danger"></i>' !!}
                        </td>
                        <td>
                            {{ actionButton('module.edit', $module->id, $title) }}
                            {{ actionButton('module.destroy', $item->id, $title) }}
                        </td>
                    </tr>
                @endforeach
                @empty
                <tr>
                    <td colspan="8" class="text-center"><i>No data.</i></td>
                </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>
</div>
@endsection