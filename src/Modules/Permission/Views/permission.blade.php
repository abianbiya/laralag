@extends('Laralag::layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />

{{-- Search + summary + add button --}}
<div class="row align-items-center mb-3 g-2">
    <div class="col-md-4">
        <form action="{{ route('permission.index') }}" method="get">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text"
                       class="form-control border-start-0 ps-0"
                       name="search"
                       value="{{ request()->get('search') }}"
                       placeholder="Cari module…"
                       autocomplete="off">
                @if(request()->filled('search'))
                    <a href="{{ route('permission.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset"><i class="bi bi-x-lg"></i></a>
                @else
                    <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-8 d-flex align-items-center justify-content-end gap-3">
        <span class="text-muted" style="font-size:13px;">
            <strong>{{ $groups->count() }}</strong> module &middot; <strong>{{ $totalPermissions }}</strong> permissions
        </span>
        {{ actionButton('permission.create', title: $title) }}
    </div>
</div>

{{-- Flash messages --}}
<div class="row mb-2">
    <div class="col-12">
        @include('Laralag::include.flash')
    </div>
</div>

{{-- Accordion groups --}}
@forelse($groups as $group => $permissions)
    @php $collapseId = 'collapse-' . Str::slug($group ?? 'ungrouped'); @endphp
    <div class="card mb-2 border">
        {{-- Group header --}}
        <div class="card-header p-0 bg-white">
            <button class="btn w-100 text-start d-flex align-items-center gap-2 py-2 px-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="true"
                    aria-controls="{{ $collapseId }}">
                <i class="bi bi-box text-primary"></i>
                <span class="fw-semibold text-dark">{{ $group ?? '(ungrouped)' }}</span>
                <span class="badge bg-primary bg-opacity-10 text-primary ms-1" style="font-size:11px;font-weight:500;">
                    {{ $permissions->count() }} permissions
                </span>
                <i class="bi bi-chevron-down ms-auto text-muted accordion-chevron" style="transition:transform .2s;transform:rotate(180deg);"></i>
            </button>
        </div>

        {{-- Group body --}}
        <div id="{{ $collapseId }}" class="collapse show">
            <div class="table-responsive">
                <table class="table table-hover table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:44px;">No</th>
                            <th>Slug</th>
                            <th>Nama</th>
                            <th class="text-center" style="width:85px;">Action</th>
                            <th class="text-center" style="width:110px;">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $i => $item)
                        @php
                            $badgeClass = match($item->action) {
                                'index'           => 'bg-success',
                                'create', 'store' => 'bg-primary',
                                'show', 'edit', 'update' => 'bg-warning text-dark',
                                'destroy'         => 'bg-danger',
                                default           => 'bg-secondary',
                            };
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $i + 1 }}</td>
                            <td><code class="text-dark">{{ $item->slug }}</code></td>
                            <td>{{ $item->nama }}</td>
                            <td class="text-center">
                                <span class="badge {{ $badgeClass }}" style="font-size:10px;">{{ $item->action }}</span>
                            </td>
                            <td class="text-center">
                                {{ actionButton('permission.show', $item->id, $title) }}
                                {{ actionButton('permission.edit', $item->id, $title) }}
                                {{ actionButton('permission.destroy', $item->id, $title) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="text-center text-muted py-5">
        <i class="bi bi-shield-slash fs-1 d-block mb-2 opacity-50"></i>
        <p class="mb-0">Tidak ada permission ditemukan.</p>
        @if(request()->filled('search'))
            <a href="{{ route('permission.index') }}" class="btn btn-sm btn-outline-secondary mt-3">Reset pencarian</a>
        @endif
    </div>
@endforelse

@endsection
@section('script')
<script>
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn => {
        const target = document.querySelector(btn.dataset.bsTarget);
        if (!target) return;
        target.addEventListener('show.bs.collapse', () => {
            btn.querySelector('.accordion-chevron').style.transform = 'rotate(180deg)';
        });
        target.addEventListener('hide.bs.collapse', () => {
            btn.querySelector('.accordion-chevron').style.transform = 'rotate(0deg)';
        });
    });
</script>
@endsection
