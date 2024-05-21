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
                <div class='col-lg-2'><p>Nama</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->nama }}</p></div>
									<div class='col-lg-2'><p>Nama En</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->nama_en }}</p></div>
									<div class='col-lg-2'><p>Urutan</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->urutan }}</p></div>
									<div class='col-lg-2'><p>Is Tampil</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->is_tampil }}</p></div>
									
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('page-css')
@endsection

@section('main')
<div class="page-heading">
    <div class="page-title">
        <div class="row mb-2">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="{{ route('menugroup.index') }}" class="btn btn-sm icon icon-left btn-outline-secondary"><i class="fa fa-arrow-left"></i> Kembali </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('menugroup.index') }}">{{ $title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $menugroup->nama }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <h6 class="card-header">
                Detail Data {{ $title }}: {{ $menugroup->nama }}
            </h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-10 offset-lg-2">
                        <div class="row">
                            <div class='col-lg-2'><p>Nama</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->nama }}</p></div>
									<div class='col-lg-2'><p>Nama En</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->nama_en }}</p></div>
									<div class='col-lg-2'><p>Urutan</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->urutan }}</p></div>
									<div class='col-lg-2'><p>Is Tampil</p></div><div class='col-lg-10'><p class='fw-bold'>{{ $menugroup->is_tampil }}</p></div>
									
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

@section('page-js')
@endsection

@section('inline-js')
@endsection