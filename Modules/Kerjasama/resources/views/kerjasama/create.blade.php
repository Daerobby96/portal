@extends('kerjasama::layouts.master')

@section('title', 'Tambah Kerjasama')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('kerjasama.index') }}">Data Kerjasama</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('page-title', 'Tambah Data Kerjasama & Mitra')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('kerjasama.store') }}" enctype="multipart/form-data">
                @csrf
                @include('kerjasama::kerjasama._form')
                
                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('kerjasama.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

