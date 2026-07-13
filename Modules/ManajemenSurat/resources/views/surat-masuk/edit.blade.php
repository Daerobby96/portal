@extends('manajemen-surat::layouts.master')

@section('title', 'Edit Surat Masuk')
@section('page-title', 'Edit Surat Masuk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Surat Masuk</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('manajemen-surat::surat-masuk._form')
        </form>
    </div>
</div>
@endsection
