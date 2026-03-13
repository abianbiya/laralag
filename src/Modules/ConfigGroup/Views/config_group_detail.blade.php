@extends('Laralag::layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <x-Laralag::breadcrumb :title='"Detail Data Config Group"' :crumbs="['dashboard.index' => 'Dashboard', 'config-group.index' => 'Config Group']" />
    <div class="row mt-2">
        <div class="col-12">
            @include('Laralag::include.flash')
        </div>
    </div>

    {{-- Group detail --}}
    <div class="row">
        <div class="col">
            <div class="table-responsive-md col-12">
                <table class="table table-hover table-nowrap mb-0">
                    <tr>
                        <th width="25%">Slug</th>
                        <td>{{ $configGroup->slug }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $configGroup->nama }}</td>
                    </tr>
                    <tr>
                        <th>Urutan</th>
                        <td>{{ $configGroup->urutan }}</td>
                    </tr>
                    <tr>
                        <th>Icon</th>
                        <td>
                            @if($configGroup->icon)
                                <i class="{{ $configGroup->icon }}"></i> <code>{{ $configGroup->icon }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tampil</th>
                        <td>{{ $configGroup->is_tampil ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            {{ actionButton('config-group.edit', $configGroup->id, $title) }}
            <a href="{{ route('config-group.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    {{-- Config items list --}}
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-semibold">Config Items</h6>
            @if(can('config-group.create'))
                <a href="{{ route('configitem.create', ['config_group_id' => $configGroup->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Config Item
                </a>
            @endif
        </div>
        <div class="col-12">
            @if($configGroup->configs->isEmpty())
                <p class="text-muted">Belum ada config item di group ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Key</th>
                                <th>Nama Config</th>
                                <th>Form Type</th>
                                <th>Default Value</th>
                                <th>Tampil</th>
                                <th>Urutan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($configGroup->configs->sortBy('urutan') as $i => $config)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><code>{{ $config->key }}</code></td>
                                <td>{{ $config->config_name ?? '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $config->form_type }}</span></td>
                                <td class="text-truncate" style="max-width:160px">{{ $config->default_value ?? '-' }}</td>
                                <td>
                                    @if($config->is_tampil)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>{{ $config->urutan }}</td>
                                <td class="text-end text-nowrap">
                                    @if(can('config-group.edit'))
                                        <a href="{{ route('configitem.edit', $config->id) }}" class="btn btn-warning btn-sm py-0 px-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    @if(can('config-group.destroy'))
                                        <form action="{{ route('configitem.destroy', $config->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus config item ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm py-0 px-1">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
<script src="{{ URL::asset('lag/libs/tom-select/js/tom-select.base.min.js') }}"></script>
<script src="{{ URL::asset('lag/js/app.js') }}"></script>
@endsection
