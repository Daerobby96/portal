@extends('dataakademik::layouts.master')

@section('title', 'Edit Prestasi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('prestasi.index') }}">Data Prestasi</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Prestasi Mahasiswa')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('prestasi.update', $prestasi) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('dataakademik::prestasi._form')
                
                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
