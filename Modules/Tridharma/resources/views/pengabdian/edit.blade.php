@extends('tridharma::layouts.master')

@section('title', 'Edit PkM')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengabdian.index') }}">Data PkM</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Data Pengabdian (PkM)')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('pengabdian.update', $pengabdian) }}">
                @csrf @method('PUT')
                @include('tridharma::pengabdian._form')
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('pengabdian.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

