@extends('Laralag::layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard']" />
<div class="mt-2">
    <form action="{{ route('module.store') }}" method="POST" class="form">
        @csrf 
        @if(request()->get('back'))
            <input type="hidden" name="back" value="{{ request()->get('back') }}">
        @endif
        @foreach ($forms as $key => $value)
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label for="{{ $key }}">{{ $value[0] }}</label>
            </div>
            <div class="col-md-6">
                {{ $value[1] }}
                @error($key)
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        @endforeach
        <div class="offset-md-3 ps-2">
            <button class="btn btn-primary btn-sm" type="submit">Simpan</button> &nbsp;
            <a href="{{ route('module.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('.routing').on('keyup', function() {
                // Get the current value of the input being typed into
                var value = $(this).val();
                
                // Set the value of both inputs to the current value
                $('.permission').val(value);
            });
        });
    </script>
@endpush