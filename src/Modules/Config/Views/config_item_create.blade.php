@extends('Laralag::layouts.master')
@section('title'){{ $title }}@endsection
@section('content')
<x-Laralag::breadcrumb :title="$title" :crumbs="['dashboard.index' => 'Dashboard', 'config-group.index' => 'Config Group']" />
<div class="mt-2">
    <form action="{{ route('configitem.store') }}" method="POST" class="form">
        @csrf

        {{-- Group --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Config Group <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <select name="config_group_id" class="form-control" required>
                    <option value="">-- Pilih Group --</option>
                    @foreach($groups as $id => $nama)
                        <option value="{{ $id }}" {{ old('config_group_id', $selectedGroup) == $id ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
                @error('config_group_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Key --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Key <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <input type="text" name="key" class="form-control" value="{{ old('key') }}" placeholder="contoh: general.app_name" required>
                <small class="text-muted">Unik, dot-notation. Digunakan untuk <code>setting('key')</code></small>
                @error('key')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Config Name --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Nama Config</label>
            </div>
            <div class="col-md-6">
                <input type="text" name="config_name" class="form-control" value="{{ old('config_name') }}" placeholder="Nama deskriptif">
                @error('config_name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Default Value --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Default Value</label>
            </div>
            <div class="col-md-6">
                <textarea name="default_value" class="form-control" rows="2" placeholder="Nilai default">{{ old('default_value') }}</textarea>
                @error('default_value')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Form Type --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Form Type <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <select name="form_type" class="form-control" id="form_type" required>
                    @foreach($formTypes as $value => $label)
                        <option value="{{ $value }}" {{ old('form_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('form_type')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Form Options (for select/checkbox) --}}
        <div class="row mb-3" id="form_options_row" style="display:none">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Form Options</label>
            </div>
            <div class="col-md-6">
                <textarea name="form_options" class="form-control font-monospace" rows="5" placeholder='[{"label":"Pilihan 1","value":"1"},{"label":"Pilihan 2","value":"2"}]'>{{ old('form_options') }}</textarea>
                <small class="text-muted">JSON array: <code>[{"label":"...","value":"..."}]</code></small>
                @error('form_options')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Is Multiple --}}
        <div class="row mb-3" id="is_multiple_row" style="display:none">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Multiple Value</label>
            </div>
            <div class="col-md-6">
                <select name="is_multiple" class="form-control">
                    <option value="0" {{ old('is_multiple', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                    <option value="1" {{ old('is_multiple') == '1' ? 'selected' : '' }}>Ya</option>
                </select>
                @error('is_multiple')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Form Label --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Form Label</label>
            </div>
            <div class="col-md-6">
                <input type="text" name="form_label" class="form-control" value="{{ old('form_label') }}" placeholder="Label pada form pengaturan">
                @error('form_label')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Form Placeholder --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Placeholder</label>
            </div>
            <div class="col-md-6">
                <input type="text" name="form_placeholder" class="form-control" value="{{ old('form_placeholder') }}" placeholder="Placeholder input">
                @error('form_placeholder')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Form Help --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Help Text</label>
            </div>
            <div class="col-md-6">
                <input type="text" name="form_help" class="form-control" value="{{ old('form_help') }}" placeholder="Teks bantuan di bawah field">
                @error('form_help')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Validation Rules --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Validation Rules</label>
            </div>
            <div class="col-md-6">
                <input type="text" name="validation_rules" class="form-control" value="{{ old('validation_rules') }}" placeholder="contoh: required|max:255">
                <small class="text-muted">Laravel validation rules string</small>
                @error('validation_rules')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Urutan --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Urutan</label>
            </div>
            <div class="col-md-6">
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', 0) }}">
                @error('urutan')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Is Tampil --}}
        <div class="row mb-3">
            <div class="col-md-3 text-sm-start text-md-end pt-2">
                <label>Tampilkan</label>
            </div>
            <div class="col-md-6">
                <select name="is_tampil" class="form-control">
                    <option value="1" {{ old('is_tampil', '1') == '1' ? 'selected' : '' }}>Ya</option>
                    <option value="0" {{ old('is_tampil') == '0' ? 'selected' : '' }}>Tidak</option>
                </select>
                @error('is_tampil')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="offset-md-3 ps-2">
            <button class="btn btn-primary btn-sm" type="submit">Simpan</button> &nbsp;
            <a href="{{ route('config-group.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
    const formTypeSelect = document.getElementById('form_type');
    const optionsRow     = document.getElementById('form_options_row');
    const multipleRow    = document.getElementById('is_multiple_row');

    function toggleOptions() {
        const val = formTypeSelect.value;
        const show = (val === 'select' || val === 'checkbox');
        optionsRow.style.display  = show ? '' : 'none';
        multipleRow.style.display = show ? '' : 'none';
    }

    formTypeSelect.addEventListener('change', toggleOptions);
    toggleOptions();
</script>
@endsection
