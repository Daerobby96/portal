@extends('datamaster::layouts.master')

@section('title', 'Edit Program Studi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('program-studi.index') }}">Program Studi</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Program Studi')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('program-studi.update', $program_studi) }}">
                @csrf @method('PUT')
                @include('datamaster::program_studi._form', ['ps' => $program_studi])
                <div class="mt-5 pt-3 border-top text-end">
                    <a href="{{ route('program-studi.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


