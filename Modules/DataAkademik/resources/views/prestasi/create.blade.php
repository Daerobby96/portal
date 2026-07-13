@extends('dataakademik::layouts.master')

@section('title', 'Tambah Prestasi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('prestasi.index') }}">Data Prestasi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('page-title', 'Tambah Prestasi Mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('prestasi.store') }}" enctype="multipart/form-data">
                @csrf
                @include('dataakademik::prestasi._form')
                
                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
