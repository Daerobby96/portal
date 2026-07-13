@extends('manajemenaset::layouts.master')

@section('title', 'Detail Aset')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Inventaris Aset</a></li>
<li class="breadcrumb-item active">{{ $aset->nama_aset }}</li>
@endsection
@section('page-title', $aset->nama_aset)
@section('page-subtitle', $aset->kode_aset)

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('pemeliharaan.create', $aset) }}" class="btn btn-outline-primary">
        <i class="bi bi-tools me-1"></i>Catat Pemeliharaan
    </a>
    <a href="{{ route('aset.edit', $aset) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <form method="POST" action="{{ route('aset.destroy', $aset) }}" class="d-inline" 
        onsubmit="return confirm('Hapus aset ini?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>Hapus
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Informasi Dasar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Aset</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Kode Aset</label>
                            <div class="fw-semibold">{{ $aset->kode_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Kategori</label>
                            <div>{!! $aset->kategori->badge !!}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Aset</label>
                            <div class="fw-semibold">{{ $aset->nama_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Program Studi</label>
                            <div>{{ $aset->prodi?->nama ?? 'Umum' }}</div>
                        </div>
                        @if($aset->merk)
                        <div class="col-md-4">
                            <label class="text-muted small">Merk</label>
                            <div>{{ $aset->merk }}</div>
                        </div>
                        @endif
                        @if($aset->tipe)
                        <div class="col-md-4">
                            <label class="text-muted small">Tipe/Model</label>
                            <div>{{ $aset->tipe }}</div>
                        </div>
                        @endif
                        @if($aset->nomor_seri)
                        <div class="col-md-4">
                            <label class="text-muted small">Nomor Seri</label>
                            <div>{{ $aset->nomor_seri }}</div>
                        </div>
                        @endif
                        @if($aset->spesifikasi)
                        <div class="col-md-12">
                            <label class="text-muted small">Spesifikasi</label>
                            <div>{{ $aset->spesifikasi }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status & Kondisi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Status & Kondisi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Kondisi</label>
                            <div>{!! $aset->kondisi_badge !!}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <div>{!! $aset->status_badge !!}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lokasi & Penempatan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Lokasi & Penempatan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Lokasi</label>
                            <div class="fw-semibold">{{ $aset->lokasi }}</div>
                        </div>
                        @if($aset->ruangan)
                        <div class="col-md-6">
                            <label class="text-muted small">Ruangan</label>
                            <div>{{ $aset->ruangan }}</div>
                        </div>
                        @endif
                        @if($aset->penanggung_jawab)
                        <div class="col-md-12">
                            <label class="text-muted small">Penanggung Jawab</label>
                            <div>{{ $aset->penanggung_jawab }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Perolehan -->
            @if($aset->tanggal_perolehan || $aset->sumber_perolehan || $aset->harga_perolehan)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Perolehan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($aset->tanggal_perolehan)
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Perolehan</label>
                            <div>{{ $aset->tanggal_perolehan->format('d M Y') }}</div>
                        </div>
                        @endif
                        @if($aset->sumber_perolehan)
                        <div class="col-md-6">
                            <label class="text-muted small">Sumber Perolehan</label>
                            <div>{{ $aset->sumber_perolehan }}</div>
                        </div>
                        @endif
                        @if($aset->harga_perolehan)
                        <div class="col-md-6">
                            <label class="text-muted small">Harga Perolehan</label>
                            <div class="fw-semibold">Rp {{ number_format($aset->harga_perolehan, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        @if($aset->umur_ekonomis)
                        <div class="col-md-6">
                            <label class="text-muted small">Umur Ekonomis</label>
                            <div>{{ $aset->umur_ekonomis }} Tahun</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Riwayat Pemeliharaan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Riwayat Pemeliharaan</h6>
                    <a href="{{ route('pemeliharaan.create', $aset) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($aset->pemeliharaans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4">Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Hasil</th>
                                    <th>Petugas</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aset->pemeliharaans->take(5) as $p)
                                <tr>
                                    <td class="px-4">{{ $p->tanggal_pemeliharaan->format('d M Y') }}</td>
                                    <td>{!! $p->jenis_badge !!}</td>
                                    <td>{!! $p->hasil_badge !!}</td>
                                    <td>{{ $p->petugas->name }}</td>
                                    <td>
                                        <a href="{{ route('pemeliharaan.show', $p) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada riwayat pemeliharaan
                    </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Peminjaman -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Riwayat Peminjaman</h6>
                </div>
                <div class="card-body p-0">
                    @if($aset->peminjamans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4">Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aset->peminjamans->take(5) as $p)
                                <tr>
                                    <td class="px-4">{{ $p->peminjam->name }}</td>
                                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                                    <td>{!! $p->status_badge !!}</td>
                                    <td>
                                        <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada riwayat peminjaman
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Foto Aset -->
            @if($aset->foto)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Foto Aset</h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ asset('storage/' . $aset->foto) }}" alt="{{ $aset->nama_aset }}" 
                        class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @endif

            <!-- Keterangan -->
            @if($aset->keterangan)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Keterangan</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $aset->keterangan }}</p>
                </div>
            </div>
            @endif

            <!-- QR Code -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">QR Code Aset</h6>
                </div>
                <div class="card-body text-center">
                    <div class="qrcode-container mb-3" id="qrcode-{{ $aset->id }}"></div>
                    <small class="text-muted">Scan untuk akses cepat</small>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <span class="text-muted">Dibuat:</span><br>
                            <span class="fw-semibold">{{ $aset->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Terakhir Diubah:</span><br>
                            <span class="fw-semibold">{{ $aset->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById('qrcode-{{ $aset->id }}'), {
        text: '{{ route("aset.show", $aset) }}',
        width: 200,
        height: 200,
        correctLevel: QRCode.CorrectLevel.H
    });
});
</script>
@endpush
@endsection
