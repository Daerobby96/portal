@extends('sdm::layouts.master')

@section('title', 'Edit Pegawai')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sdm.pegawai.index') }}">Manajemen Pegawai</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Pegawai: ' . $pegawai->nama)

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="bi bi-pencil text-primary me-2"></i>Edit Data Pegawai</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sdm.pegawai.update', $pegawai) }}">
                        @csrf @method('PUT')
                        @include('sdm::pegawai._form', ['pegawai' => $pegawai])
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('sdm.pegawai.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


