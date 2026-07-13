@extends('tridharma::layouts.master')

@section('title', 'Tambah Publikasi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('publikasi.index') }}">Data Publikasi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('page-title', 'Tambah Data Publikasi Dosen')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('publikasi.store') }}">
                @csrf
                @include('tridharma::publikasi._form')
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('publikasi.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

