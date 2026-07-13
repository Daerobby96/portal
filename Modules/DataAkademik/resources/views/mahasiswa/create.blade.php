@extends('dataakademik::layouts.master')

@section('title', 'Tambah Mahasiswa')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('page-title', 'Tambah Mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('mahasiswa.store') }}">
                @csrf
                
                @include('dataakademik::mahasiswa._form')

                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
