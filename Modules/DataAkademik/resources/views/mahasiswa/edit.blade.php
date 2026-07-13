@extends('dataakademik::layouts.master')

@section('title', 'Edit Mahasiswa')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.show', $mahasiswa) }}">{{ $mahasiswa->nim }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Data Mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('mahasiswa.update', $mahasiswa) }}">
                @csrf
                @method('PUT')
                
                @include('dataakademik::mahasiswa._form')

                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
