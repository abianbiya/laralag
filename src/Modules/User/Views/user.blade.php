@extends('Laralag::layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />
<div class="row">
    <div class="col-9">
        <form action="{{ route('user.index') }}" method="get">
            <div class="form-group col-md-3 form-icon right position-relative">
                <input type="text" class="form-control form-control-sm form-control-icon" value="{{ request()->get('search') }}" name="search" placeholder="Search">
                <i class="bi bi-search"></i>
            </div>
        </form>
    </div>
    <div class="col-3">
        {{ actionButton('user.create', title: $title) }}
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
                <th scope="col">Username</th>
			    <th scope="col">Email</th>
			    <th scope="col">Name</th>
			    <th scope="col">Nomor Identitas</th>
			    <th scope="col">Opsi</th>
            </tr>
        <tbody>
            @php $no = $data->firstItem(); @endphp
            @forelse ($data as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $item->username }}</td>
				<td>{{ $item->email }}</td>
				<td>{{ $item->name }}</td>
				<td>{{ $item->identitas }}</td>
				<td>
                    {{ actionButton('user.show', $item->id, $title) }}
                    {{ actionButton('user.edit', $item->id, $title) }}
                    {{ actionButton('user.destroy', $item->id, $title) }}

                    {{-- untuk custom button dapat menggunakan fungsi di bawah atau menggunakan fungsi laravel-html  --}}
                    {{-- {{ customButton('user.blabla', $item->id, $title, 'bi bi-info-square', 'btn-outline-primary') }} --}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center"><i>No data.</i></td>
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