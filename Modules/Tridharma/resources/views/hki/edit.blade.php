@extends('layouts.app')

@section('title', 'Edit HKI')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('hki.index') }}">Data HKI</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Data HKI & Paten')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('hki.update', $hki) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('tridharma::hki._form')
                
                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('hki.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
