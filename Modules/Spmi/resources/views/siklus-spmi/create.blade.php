@extends('layouts.app')

@section('title', 'Buat Siklus Mutu Baru')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold mb-1">Buat Siklus Mutu Baru</h5>
            <p class="text-muted small mb-0">Siklus mutu mengikat beberapa periode akademik menjadi satu siklus PPEPP yang dapat dilacak dan dibandingkan.</p>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('siklus-spmi.store') }}" method="POST">
                @csrf
                @include('spmi::siklus-spmi._form')
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('siklus-spmi.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Siklus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
