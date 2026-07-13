@extends('manajemenrapat::layouts.master')

@section('title', $rapat->judul)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('rapat.index') }}">Manajemen Rapat</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('page-title', $rapat->judul)
@section('page-subtitle', \Modules\ManajemenRapat\Models\Rapat::jenisOptions()[$rapat->jenis] . ' · ' . $rapat->tanggal->format('d M Y'))

@section('page-actions')
<div class="d-flex gap-2 flex-wrap">
    {!! $rapat->status_badge !!}
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
        @unless($rapat->isLocked())
        <a href="{{ route('rapat.edit', $rapat) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endunless
        {{-- Ubah Status --}}
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalStatus">
            <i class="bi bi-arrow-repeat me-1"></i>Ubah Status
        </button>
    @endif
    <a href="{{ route('rapat.export-pdf', $rapat) }}" class="btn btn-outline-danger btn-sm" target="_blank">
        <i class="bi bi-file-pdf me-1"></i>Export PDF
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">

{{-- Validation errors (layout sudah handle session flash) --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    {{-- Kolom Kiri --}}
    <div class="col-lg-8">
        @include('manajemenrapat::_partials.info-rapat')
        @include('manajemenrapat::_partials.agenda-notulensi')
        @include('manajemenrapat::_partials.tindak-lanjut')
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-lg-4">
        @include('manajemenrapat::_partials.peserta')
        @include('manajemenrapat::_partials.lampiran')
    </div>
</div>

</div>

@include('manajemenrapat::_partials.modal-status')
@include('manajemenrapat::_partials.modal-peserta')
@endsection

@push('scripts')
<script>
// Auto-buka modal yang memiliki data-show="true" (ada validation error)
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.modal[data-show="true"]').forEach(function (el) {
        new bootstrap.Modal(el).show();
    });
});
</script>
@endpush


