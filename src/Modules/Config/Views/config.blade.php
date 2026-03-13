@extends('Laralag::layouts.master')
@section('title'){{ $title }}@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />
<div class="row mt-2">
    <div class="col-12">
        @include('Laralag::include.flash')
    </div>
</div>

@if($groups->isEmpty())
    <div class="alert alert-info">Tidak ada pengaturan yang dapat ditampilkan.</div>
@else
<div class="row">
    {{-- Tab Navigation --}}
    <div class="col-md-3">
        <div class="list-group">
            @foreach($groups as $group)
            <a href="{{ route('config.index', ['tab' => $group->slug]) }}"
               class="list-group-item list-group-item-action {{ $activeSlug === $group->slug ? 'active' : '' }}">
                @if($group->icon)<i class="{{ $group->icon }} me-1"></i>@endif
                {{ $group->nama }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="col-md-9">
        @foreach($groups as $group)
            @if($activeSlug === $group->slug)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ $group->nama }}</h5>

                    <form action="{{ route('config.update', $group->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @forelse($group->configs as $config)
                        <div class="row mb-3">
                            <div class="col-md-3 text-sm-start text-md-end pt-2">
                                <label for="config_{{ $config->id }}">
                                    {{ $config->form_label ?: $config->config_name }}
                                </label>
                            </div>
                            <div class="col-md-7">
                                @switch($config->form_type)
                                    @case('textarea')
                                        <textarea name="config_{{ $config->id }}" id="config_{{ $config->id }}"
                                            class="form-control" rows="3"
                                            placeholder="{{ $config->form_placeholder }}">{{ $config->value }}</textarea>
                                        @break

                                    @case('select')
                                        <select name="config_{{ $config->id }}{{ $config->is_multiple ? '[]' : '' }}"
                                            id="config_{{ $config->id }}"
                                            class="form-control"
                                            {{ $config->is_multiple ? 'multiple' : '' }}>
                                            <option value="">-- Pilih --</option>
                                            @if($config->form_options)
                                                @foreach($config->form_options as $option)
                                                    @php
                                                        $selected = $config->is_multiple
                                                            ? in_array($option['value'], (array) json_decode($config->value, true))
                                                            : $config->value == $option['value'];
                                                    @endphp
                                                    <option value="{{ $option['value'] }}" {{ $selected ? 'selected' : '' }}>
                                                        {{ $option['label'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @break

                                    @case('checkbox')
                                        @if($config->form_options)
                                            @foreach($config->form_options as $option)
                                                @php
                                                    $checked = in_array($option['value'], (array) json_decode($config->value, true));
                                                @endphp
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="config_{{ $config->id }}[]"
                                                        value="{{ $option['value'] }}"
                                                        id="config_{{ $config->id }}_{{ $loop->index }}"
                                                        {{ $checked ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="config_{{ $config->id }}_{{ $loop->index }}">
                                                        {{ $option['label'] }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                        @break

                                    @case('toggle')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                name="config_{{ $config->id }}"
                                                id="config_{{ $config->id }}"
                                                value="1"
                                                {{ $config->value == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="config_{{ $config->id }}"></label>
                                        </div>
                                        @break

                                    @case('file')
                                        <input type="file"
                                            name="config_{{ $config->id }}"
                                            id="config_{{ $config->id }}"
                                            class="form-control">
                                        @if($config->value)
                                            <small class="text-muted">Saat ini: {{ $config->value }}</small>
                                        @endif
                                        @break

                                    @default
                                        {{-- text, number, email, password, color --}}
                                        <input
                                            type="{{ $config->form_type ?: 'text' }}"
                                            name="config_{{ $config->id }}"
                                            id="config_{{ $config->id }}"
                                            class="form-control"
                                            value="{{ $config->form_type === 'password' ? '' : $config->value }}"
                                            placeholder="{{ $config->form_placeholder }}">
                                @endswitch

                                @if($config->form_help)
                                    <small class="text-muted">{{ $config->form_help }}</small>
                                @endif

                                @error("config_{$config->id}")
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @empty
                            <p class="text-muted"><i>Tidak ada pengaturan dalam grup ini.</i></p>
                        @endforelse

                        @if(can("config-{$group->slug}.update"))
                        <div class="offset-md-3 ps-2 mt-3">
                            <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif
@endsection
@section('script')
<script src="{{ URL::asset('lag/libs/tom-select/js/tom-select.base.min.js') }}"></script>
<script src="{{ URL::asset('lag/js/app.js') }}"></script>
@endsection
