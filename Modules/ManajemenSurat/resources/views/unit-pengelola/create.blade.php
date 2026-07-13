@extends('manajemen-surat::layouts.master')

@section('title', 'Tambah Unit Pengelola')
@section('page-title', 'Tambah Unit Pengelola')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('unit-pengelola.index') }}">Unit Pengelola</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form method="POST" action="{{ route('unit-pengelola.store') }}" enctype="multipart/form-data">
            @csrf
            @include('manajemen-surat::unit-pengelola._form')
        </form>
    </div>
</div>
@endsection
