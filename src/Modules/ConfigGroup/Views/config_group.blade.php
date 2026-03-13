@extends('Laralag::layouts.master')
@section('title'){{ $title }}@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />
<div class="row">
    <div class="col-9">
        <form action="{{ route('config-group.index') }}" method="get">
            <div class="form-group col-md-3 form-icon right position-relative">
                <input type="text" class="form-control form-control-sm form-control-icon" value="{{ request()->get('search') }}" name="search" placeholder="Search">
                <i class="bi bi-search"></i>
            </div>
        </form>
    </div>
    <div class="col-3">
        {{ actionButton('config-group.create', title: $title) }}
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">@include('Laralag::include.flash')</div>
</div>
<div class="table-responsive-md col-12">
    <table class="table table-hover table-nowrap mb-0">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama</th>
                <th>Slug</th>
                <th>Urutan</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = $data->firstItem(); @endphp
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->slug }}</td>
                <td>{{ $item->urutan }}</td>
                <td>
                    {{ actionButton('config-group.show', $item->id, $title) }}
                    {{ actionButton('config-group.edit', $item->id, $title) }}
                    {{ actionButton('config-group.destroy', $item->id, $title) }}
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center"><i>No data.</i></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="row mt-2">
    <div class="col">
        <div class="d-flex justify-content-end">{{ $data->links() }}</div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('lag/libs/tom-select/js/tom-select.base.min.js') }}"></script>
<script src="{{ URL::asset('lag/js/app.js') }}"></script>
@endsection
