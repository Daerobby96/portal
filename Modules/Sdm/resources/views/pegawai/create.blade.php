@extends('sdm::layouts.master')

@section('title', 'Tambah Pegawai')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sdm.pegawai.index') }}">Manajemen Pegawai</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('page-title', 'Tambah Pegawai')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="bi bi-person-plus text-primary me-2"></i>Data Pegawai Baru</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sdm.pegawai.store') }}">
                        @csrf
                        @include('sdm::pegawai._form')
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('sdm.pegawai.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


