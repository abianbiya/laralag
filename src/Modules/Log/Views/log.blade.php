@extends('Laralag::layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />
<div class="row">
    <div class="col-9">
        <form action="{{ route('log.index') }}" method="get">
            <div class="form-group col-md-3 form-icon right position-relative">
                <input type="text" class="form-control form-control-sm form-control-icon" value="{{ request()->get('search') }}" name="search" placeholder="Search">
                <i class="bi bi-search"></i>
            </div>
        </form>
    </div>
    <div class="col-3">
        {{ actionButton('log.create', title: $title) }}
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        @include('Laralag::include.flash')
    </div>
</div>
<div class="table-responsive-md col-12">
    <!-- Hoverable Rows -->
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th scope="col" class="text-center">No</th>
                <th scope="col">User</th>
			    <th scope="col">Name</th>
			    <th scope="col">Aktivitas</th>
			    <th scope="col">Route</th>
			    <th scope="col">Action</th>
			    <th scope="col">Context</th>
			    <th scope="col">Data From</th>
			    <th scope="col">Data To</th>
			    <th scope="col">Ip Address</th>
			    <th scope="col">User Agent</th>
			    <th scope="col">Opsi</th>
            </tr>
        <tbody>
            @php $no = $data->firstItem(); @endphp
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $item->user->nama_user }}</td>
				<td>{{ $item->name }}</td>
				<td>{!! $item->aktivitas !!}</td>
				<td>{{ $item->route }}</td>
				<td>{{ $item->action }}</td>
				<td>{{ $item->context }}</td>
				<td>{{ $item->data_from }}</td>
				<td>{{ $item->data_to }}</td>
				<td>{{ $item->ip_address }}</td>
				<td>{{ $item->user_agent }}</td>
				<td>
                    {{ actionButton('log.show', $item->id, $title) }}
                    {{ actionButton('log.edit', $item->id, $title) }}
                    {{ actionButton('log.destroy', $item->id, $title) }}

                    {{-- untuk custom button dapat menggunakan fungsi di bawah atau menggunakan fungsi laravel-html  --}}
                    {{-- {{ customButton('log.blabla', $item->id, $title, 'bi bi-info-square', 'btn-outline-primary') }} --}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center"><i>No data.</i></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="row mt-2">
    <div class="col">
        <div class="d-flex justify-content-end">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection