@extends('manajemen-surat::layouts.master')

@section('title', 'Buat Surat Keluar')
@section('page-title', 'Buat Surat Keluar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surat-keluar.index') }}">Surat Keluar</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        {{-- Info Tooltip --}}
        <div class="alert alert-light border rounded-xl mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-lightbulb text-warning"></i>
            <small class="text-muted mb-0">
                <strong>Tips:</strong> Gunakan tombol template di bawah editor untuk insert format surat standar yang bisa Anda edit sesuai kebutuhan.
            </small>
        </div>

        <form method="POST" action="{{ route('surat-keluar.store') }}">
            @csrf
            @include('manajemen-surat::surat-keluar._form')
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = Jodit.make('#isi_surat', {
        height: 500,
        language: 'id',
        toolbarButtonSize: 'small',
        buttons: 'bold,italic,underline,|,ul,ol,|,align,|,font,fontsize,brush,|,table,link,|,undo,redo,|,hr,|,fullsize',
        placeholder: 'Tulis isi surat di sini...'
    });

    // Template Surat
    const templates = {
        surat_tugas: `<p style="text-align: justify;">Untuk melaksanakan tugas sebagai berikut:</p>
<ol>
    <li>...</li>
    <li>...</li>
</ol>
<p style="text-align: justify;">Surat tugas ini berlaku sejak tanggal ditetapkan sampai dengan selesainya tugas.</p>`,
        
        surat_undangan: `<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="width: 150px; padding: 5px 0;">Hari/Tanggal</td>
        <td style="width: 20px;">:</td>
        <td>...</td>
    </tr>
    <tr>
        <td style="padding: 5px 0;">Waktu</td>
        <td>:</td>
        <td>...</td>
    </tr>
    <tr>
        <td style="padding: 5px 0;">Tempat</td>
        <td>:</td>
        <td>...</td>
    </tr>
    <tr>
        <td style="padding: 5px 0;">Acara</td>
        <td>:</td>
        <td>...</td>
    </tr>
</table>`,
        
        surat_keterangan: `<p style="text-align: justify;">Yang namanya tersebut di atas adalah benar:</p>
<ol>
    <li>...</li>
    <li>...</li>
</ol>
<p style="text-align: justify;">Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>`,
        
        surat_edaran: `<p style="text-align: justify;">Sehubungan dengan [sebutkan konteks], dengan ini kami sampaikan hal-hal sebagai berikut:</p>
<ol>
    <li>...</li>
    <li>...</li>
</ol>
<p style="text-align: justify;">Demikian surat edaran ini disampaikan untuk menjadi perhatian dan dilaksanakan dengan sebaik-baiknya.</p>`
    };

    // Button insert template
    const btnGroup = document.createElement('div');
    btnGroup.className = 'mb-3';
    btnGroup.innerHTML = `
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTemplate('surat_tugas')">
            <i class="bi bi-file-text"></i> Template Surat Tugas
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTemplate('surat_undangan')">
            <i class="bi bi-calendar-event"></i> Template Undangan
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTemplate('surat_keterangan')">
            <i class="bi bi-award"></i> Template Keterangan
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTemplate('surat_edaran')">
            <i class="bi bi-megaphone"></i> Template Edaran
        </button>
    `;
    editor.container.parentNode.insertBefore(btnGroup, editor.container);

    window.insertTemplate = function(type) {
        editor.value = templates[type];
    };
});
</script>
@endpush
