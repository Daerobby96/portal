@extends('layouts.app')

@section('title', 'Siklus Penjaminan Mutu Internal (PPEPP)')

@section('content')
<div class="container-fluid px-0">
    {{-- Header Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-[#131b2e] to-slate-900 p-6 text-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] mb-6 group border border-slate-800/40">
        <div class="absolute -right-6 -top-6 h-36 w-36 rounded-full bg-primary/10 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
        <div class="absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-emerald-500/5 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
        
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 border border-primary/20 px-3 py-1 text-xs font-bold text-primary mb-3">
                    Siklus Nasional Dikti
                </span>
                <h5 class="text-2xl font-extrabold mb-1 tracking-tight">
                    Siklus Penjaminan Mutu Internal <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">(PPEPP)</span>
                </h5>
                <p class="text-slate-300 text-sm mt-2">
                    Pemantauan alur penjaminan mutu komprehensif untuk Periode: <strong>{{ $periode->nama }}</strong>
                </p>
            </div>
            
            <div class="flex items-center gap-3 bg-slate-800/50 border border-slate-700/50 p-4 rounded-2xl">
                <div class="text-center">
                    <span class="text-2xl font-black text-white block">{{ $ppeppDetails['overall'] }}%</span>
                    <span class="text-[10px] uppercase font-bold text-slate-400">Total Ketercapaian</span>
                </div>
                <div class="progress progress-bar-vertical" style="width: 8px; height: 40px; background-color: #334155; border-radius: 4px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="height: {{ $ppeppDetails['overall'] }}%; width: 100%; border-radius: 4px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- PPEPP Interactive Step Tracker --}}
    <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-6 overflow-hidden">
        <div class="card-body p-4 p-lg-5">
            <div class="position-relative d-flex justify-content-between align-items-start w-100 ppepp-tracker">
                <!-- Line background -->
                <div class="ppepp-line position-absolute start-0 w-100" style="height: 4px; background: #f1f5f9; z-index: 1; top: 25px;"></div>
                
                @php
                    $steps = [
                        ['id' => 'penetapan', 'label' => 'Penetapan (P1)', 'icon' => 'bi-file-earmark-check', 'desc' => 'Standar & IKU', 'progress' => $ppeppDetails['penetapan']],
                        ['id' => 'pelaksanaan', 'label' => 'Pelaksanaan (P2)', 'icon' => 'bi-play-circle', 'desc' => 'Input Capaian & Bukti', 'progress' => $ppeppDetails['pelaksanaan']],
                        ['id' => 'evaluasi', 'label' => 'Evaluasi (P3)', 'icon' => 'bi-search', 'desc' => 'Audit Mutu Internal', 'progress' => $ppeppDetails['evaluasi']],
                        ['id' => 'pengendalian', 'label' => 'Pengendalian (P4)', 'icon' => 'bi-shield-check', 'desc' => 'Tindak Lanjut Temuan', 'progress' => $ppeppDetails['pengendalian']],
                        ['id' => 'peningkatan', 'label' => 'Peningkatan (P5)', 'icon' => 'bi-graph-up-arrow', 'desc' => 'RTM & Upgrade Standar', 'progress' => $ppeppDetails['peningkatan']]
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    @php
                        $progress = $step['progress'];
                        $isCompleted = $progress >= 100;
                        $isActive = !$isCompleted && $progress > 0;
                        
                        $statusClass = $isCompleted ? 'completed' : ($isActive ? 'active' : 'pending');
                        $colorClass = $isCompleted ? 'bg-emerald-500 shadow-emerald-500/20 text-white' : ($isActive ? 'bg-primary shadow-primary/20 text-white' : 'bg-slate-100 text-slate-400');
                        $textColor = $isCompleted || $isActive ? 'text-white' : 'text-slate-400';
                    @endphp
                    
                    <button class="ppepp-step text-center position-relative border-0 bg-transparent nav-link w-100 p-0 {{ $statusClass }}" 
                            onclick="switchPpeppTab('{{ $step['id'] }}')" 
                            id="btn-step-{{ $step['id'] }}"
                            style="z-index: 2; flex: 1; min-width: 80px;">
                        <div class="step-icon-wrapper mx-auto mb-3 d-flex align-items-center justify-content-center shadow-lg {{ $colorClass }} {{ $textColor }} transition-all duration-300" 
                              style="width: 52px; height: 52px; border-radius: 16px; border: 4px solid #fff;">
                            <i class="bi {{ $step['icon'] }} fs-5"></i>
                        </div>
                        <div class="step-label font-bold text-slate-800" style="font-size: 0.88rem;">{{ $step['label'] }}</div>
                        <div class="step-desc text-slate-400 mt-1 d-none d-md-block" style="font-size: 0.74rem;">{{ $step['desc'] }}</div>
                        
                        <div class="mt-2.5">
                            @if($isCompleted)
                                <span class="badge bg-emerald-50 text-emerald-600 border border-emerald-200/50 rounded-pill font-bold px-2 py-1 text-[10px]">
                                    100% Selesai
                                </span>
                            @elseif($isActive)
                                <span class="badge bg-blue-50 text-blue-600 border border-blue-200/50 rounded-pill font-bold px-2 py-1 text-[10px]">
                                    {{ $progress }}% Proses
                                </span>
                            @else
                                <span class="badge bg-slate-50 text-slate-400 border border-slate-200/50 rounded-pill font-bold px-2 py-1 text-[10px]">
                                    Menunggu
                                </span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Detail Pane Containers --}}
    <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        {{-- TAB 1: Penetapan --}}
        <div class="ppepp-tab-content p-4" id="pane-penetapan">
            <div class="border-b border-slate-100 pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-bold text-slate-800"><i class="bi bi-file-earmark-check text-primary me-2"></i>Fase 1: Penetapan Standar Mutu</h5>
                    <p class="text-xs text-slate-400 mb-0">Penetapan target mutu institusi berupa Standar SPMI dan Indikator Kinerja Utama/Tambahan.</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-slate-50 text-slate-700 border rounded-xl px-3 py-2 font-bold text-xs"><i class="bi bi-bookmark text-primary me-1"></i>{{ $totalStandar }} Standar Mutu</span>
                    <span class="badge bg-slate-50 text-slate-700 border rounded-xl px-3 py-2 font-bold text-xs"><i class="bi bi-bullseye text-primary me-1"></i>{{ $totalIndikator }} Indikator Kinerja</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Kode</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Nama Standar</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Bidang Mutu</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Jenis</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Dokumen Mutu (Approved)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($standars as $std)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/30">
                            <td class="px-4 py-3 font-bold text-primary">{{ $std->kode }}</td>
                            <td class="px-4 py-3 text-slate-800 font-semibold small">{{ $std->nama }}</td>
                            <td class="px-4 py-3">{!! $std->bidang_badge !!}</td>
                            <td class="px-4 py-3 text-capitalize small">{{ $std->jenis }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($std->dokumens_count > 0)
                                    <span class="badge bg-blue-50 text-blue-600 rounded-pill px-2.5 py-1 text-xs font-bold border border-blue-200/50">
                                        {{ $std->dokumens_count }} Dokumen
                                    </span>
                                @else
                                    <div class="d-flex align-items-center justify-content-center gap-1.5 flex-wrap">
                                        <span class="badge bg-red-50 rounded-pill px-2.5 py-1 text-xs font-bold border">
                                            Belum Ada Dokumen
                                        </span>
                                        @if(auth()->user()->canManageDokumen() || auth()->user()->isSuperAdmin())
                                            <a href="{{ route('dokumen.create', ['standar_id' => $std->id]) }}" class="btn btn-outline-danger btn-sm py-0.5 px-2 text-[10px] font-bold" style="border-radius: 8px !important; padding: 2px 8px !important;">
                                                <i class="bi bi-plus-lg me-0.5"></i>Tambah
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-5">Belum ada Standar Mutu yang ditetapkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: Pelaksanaan --}}
        <div class="ppepp-tab-content p-4 d-none" id="pane-pelaksanaan">
            <div class="border-b border-slate-100 pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-bold text-slate-800"><i class="bi bi-play-circle text-primary me-2"></i>Fase 2: Pelaksanaan & Monitoring</h5>
                    <p class="text-xs text-slate-400 mb-0">Pelaksanaan program kerja dan penginputan bukti pencapaian indikator secara berkala.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small font-bold text-slate-500">Keterisian Kinerja:</span>
                    <span class="badge bg-blue-50 text-blue-600 border border-blue-200/50 rounded-pill font-bold px-2.5 py-1.5 text-xs">
                        {{ $pelaksanaanProgress }}% Kinerja Terinput
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Indikator Kinerja</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Target Nilai</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Nilai Capaian</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Persentase</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Status</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Berkas Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitorings as $mon)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/30">
                            <td class="px-4 py-3">
                                <span class="d-block font-bold text-slate-800 small">{{ $mon->indikator->kode }}</span>
                                <span class="text-slate-400 text-xs">{{ $mon->indikator->nama }}</span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-slate-700 small">{{ $mon->indikator->target_nilai }} {{ $mon->indikator->satuan }}</td>
                            <td class="px-4 py-3 text-center font-bold text-primary small">{{ $mon->nilai_capaian }} {{ $mon->indikator->satuan }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold {{ $mon->persentase_capaian >= 100 ? 'text-emerald-500' : 'text-amber-500' }}">{{ $mon->persentase_capaian }}%</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($mon->is_tercapai)
                                    <span class="badge bg-emerald-50 text-emerald-600 border border-emerald-200/50 rounded-pill px-2.5 py-1 text-xs font-bold">Tercapai</span>
                                @else
                                    <span class="badge bg-rose-50 text-rose-600 border border-rose-200/50 rounded-pill px-2.5 py-1 text-xs font-bold">Belum Tercapai</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($mon->bukti_dokumen)
                                    <a href="{{ asset('storage/' . $mon->bukti_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2.5"><i class="bi bi-file-earmark-text me-1"></i>Lihat Bukti</a>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-5">Belum ada data capaian kinerja yang dimasukkan untuk periode berjalan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 3: Evaluasi --}}
        <div class="ppepp-tab-content p-4 d-none" id="pane-evaluasi">
            <div class="border-b border-slate-100 pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-bold text-slate-800"><i class="bi bi-search text-primary me-2"></i>Fase 3: Evaluasi & Audit Mutu Internal (AMI)</h5>
                    <p class="text-xs text-slate-400 mb-0">Evaluasi pemenuhan standar mutu melalui instrumen audit lapangan secara komprehensif.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small font-bold text-slate-500">Penyelesaian Audit:</span>
                    <span class="badge bg-blue-50 text-blue-600 border border-blue-200/50 rounded-pill font-bold px-2.5 py-1.5 text-xs">
                        {{ $evaluasiProgress }}% Audit Selesai
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Kode Audit</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Nama Audit</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Unit Diaudit</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Ketua Auditor</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Jumlah Checklist</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audits as $aud)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/30">
                            <td class="px-4 py-3 font-bold"><code class="text-primary font-bold bg-primary/5 px-2 py-1 rounded text-xs">{{ $aud->kode_audit }}</code></td>
                            <td class="px-4 py-3 text-slate-800 font-semibold small">{{ $aud->nama_audit }}</td>
                            <td class="px-4 py-3 text-slate-600 small">{{ $aud->unit_yang_diaudit }}</td>
                            <td class="px-4 py-3 text-slate-600 small">{{ $aud->ketuaAuditor->name }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-slate-50 text-slate-700 border px-2 py-1 text-xs">{{ $aud->checklists_count }} Poin</span>
                            </td>
                            <td class="px-4 py-3">{!! $aud->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-5">Belum ada agenda Audit Mutu Internal (AMI) pada periode berjalan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 4: Pengendalian --}}
        <div class="ppepp-tab-content p-4 d-none" id="pane-pengendalian">
            <div class="border-b border-slate-100 pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-bold text-slate-800"><i class="bi bi-shield-check text-primary me-2"></i>Fase 4: Pengendalian & Tindak Lanjut</h5>
                    <p class="text-xs text-slate-400 mb-0">Penyelesaian ketidaksesuaian/temuan audit melalui perbaikan oleh auditee dan verifikasi auditor.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small font-bold text-slate-500">Penyelesaian Temuan:</span>
                    <span class="badge bg-blue-50 text-blue-600 border border-blue-200/50 rounded-pill font-bold px-2.5 py-1.5 text-xs">
                        {{ $pengendalianProgress }}% Temuan Ditangani
                    </span>
                </div>
            </div>

            {{-- Stat badges --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-100 text-center">
                        <span class="text-rose-600 font-extrabold text-xl d-block">{{ $temuanStats['open'] }}</span>
                        <span class="text-[10px] text-rose-500 uppercase font-bold">Temuan Open</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 text-center">
                        <span class="text-amber-600 font-extrabold text-xl d-block">{{ $temuanStats['closed'] }}</span>
                        <span class="text-[10px] text-amber-500 uppercase font-bold">Tindak Lanjut Masuk</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100 text-center">
                        <span class="text-emerald-600 font-extrabold text-xl d-block">{{ $temuanStats['verified'] }}</span>
                        <span class="text-[10px] text-emerald-500 uppercase font-bold">Verified (Closed Loop)</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <span class="text-slate-600 font-extrabold text-xl d-block">{{ $temuanStats['total'] }}</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold">Total Temuan</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Kode Temuan</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Uraian Temuan</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Kategori</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Tenggat</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($temuans as $tem)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/30">
                            <td class="px-4 py-3 font-bold"><code class="text-danger bg-danger/5 px-2.5 py-1 rounded text-xs">{{ $tem->kode_temuan }}</code></td>
                            <td class="px-4 py-3 text-slate-800 small">{{ $tem->uraian_temuan }}</td>
                            <td class="px-4 py-3">{!! $tem->kategori_badge !!}</td>
                            <td class="px-4 py-3 text-slate-500 small">{{ $tem->batas_tindak_lanjut ? $tem->batas_tindak_lanjut->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($tem->status === 'verified')
                                    <span class="badge bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-pill font-bold text-xs"><i class="bi bi-patch-check me-1"></i>Verified</span>
                                @elseif($tem->status === 'closed')
                                    <span class="badge bg-amber-100 text-amber-700 px-2.5 py-1 rounded-pill font-bold text-xs"><i class="bi bi-clock me-1"></i>Tinjauan</span>
                                @else
                                    <span class="badge bg-rose-100 text-rose-700 px-2.5 py-1 rounded-pill font-bold text-xs"><i class="bi bi-exclamation-triangle me-1"></i>Open</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-5">Hebat! Belum ditemukan atau tidak ada KTS pada audit periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 5: Peningkatan --}}
        <div class="ppepp-tab-content p-4 d-none" id="pane-peningkatan">
            <div class="border-b border-slate-100 pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-bold text-slate-800"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Fase 5: Peningkatan Standar Mutu</h5>
                    <p class="text-xs text-slate-400 mb-0">Rencana perbaikan standar, tindak lanjut Rapat Tinjauan Manajemen (RTM), dan penetapan target standar baru yang ditingkatkan.</p>
                </div>
                <span class="badge bg-blue-50 text-blue-600 border border-blue-200/50 rounded-pill font-bold px-2.5 py-1.5 text-xs">
                    {{ $peningkatanProgress }}% Peningkatan Selesai
                </span>
            </div>

            @if($ppeppDetails['is_loop_closed'])
            <div class="alert alert-success border border-emerald-200 bg-emerald-50/50 rounded-2xl p-4 mb-4">
                <h6 class="font-bold text-emerald-800 mb-1"><i class="bi bi-award-fill me-2 fs-5"></i>Siklus Mutu PPEPP Berhasil Ditutup Sempurna (Full Loop Closed)!</h6>
                <p class="text-xs text-emerald-600 mb-0">Seluruh dokumen standar, monitoring program kerja, audit mutu internal, penyelesaian tindak lanjut temuan, dan rapat tinjauan manajemen telah selesai 100%. Institusi Anda berada di jalur pemenuhan standar mutu BAN-PT Unggul.</p>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Judul Rapat</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Tanggal Rapat</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Keputusan Manajemen / Output</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Status</th>
                            <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Dokumen RTM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rtms as $rtm)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/30">
                            <td class="px-4 py-3 font-semibold text-slate-800 small">{{ $rtm->judul_rapat }}</td>
                            <td class="px-4 py-3 text-slate-600 small">{{ $rtm->tanggal_rapat->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-500 small text-truncate" style="max-width: 250px;" title="{{ $rtm->keputusan_manajemen }}">{{ $rtm->keputusan_manajemen ?? 'Dalam Penyusunan...' }}</td>
                            <td class="px-4 py-3">
                                @if($rtm->status === 'selesai')
                                    <span class="badge bg-emerald-50 text-emerald-600 rounded-pill px-2.5 py-1 text-xs font-bold border border-emerald-200/50">Selesai</span>
                                @else
                                    <span class="badge bg-amber-50 text-amber-600 rounded-pill px-2.5 py-1 text-xs font-bold border border-amber-200/50">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($rtm->status === 'selesai')
                                    <a href="{{ route('rtm.export-pdf', $rtm) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5"><i class="bi bi-download me-1"></i>Unduh Laporan Mutu</a>
                                @else
                                    <span class="text-slate-400 text-xs">Belum diterbitkan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-5">Belum ada agenda Rapat Tinjauan Manajemen (RTM) yang dilaksanakan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Premium visual styling for active step tabs */
    .ppepp-step {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .ppepp-step:hover {
        transform: translateY(-2px);
    }
    .ppepp-step .step-icon-wrapper {
        transition: all 0.3s ease;
    }
    .ppepp-step.active-step .step-icon-wrapper {
        transform: scale(1.15);
        box-shadow: 0 0 0 5px rgba(var(--primary-color-rgb), 0.25) !important;
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
    }
    .ppepp-step.active-step .step-label {
        color: var(--primary-color) !important;
        font-weight: 800 !important;
    }

    @media (max-width: 768px) {
        .ppepp-tracker {
            flex-direction: column;
            gap: 30px;
            align-items: flex-start !important;
            padding-left: 20px;
        }
        .ppepp-line {
            width: 4px !important;
            height: 100% !important;
            left: 45px !important;
            top: 0 !important;
        }
        .ppepp-step { 
            width: 100% !important; 
            text-align: left !important;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .ppepp-step .step-icon-wrapper {
            margin-bottom: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .ppepp-step .step-label {
            margin-bottom: 0 !important;
        }
        .ppepp-step .badge {
            margin-top: 0 !important;
            margin-left: 10px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // JS tab switcher for high speed interactive page responsiveness
    function switchPpeppTab(tabId) {
        // Remove active visual class from all step buttons
        document.querySelectorAll('.ppepp-step').forEach(btn => {
            btn.classList.remove('active-step');
        });

        // Add active class to clicked button
        const activeBtn = document.getElementById('btn-step-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('active-step');
        }

        // Hide all tab panes
        document.querySelectorAll('.ppepp-tab-content').forEach(pane => {
            pane.classList.add('d-none');
        });

        // Show active tab pane
        const activePane = document.getElementById('pane-' + tabId);
        if (activePane) {
            activePane.classList.remove('d-none');
        }
    }

    // Set default active tab (Penetapan) on page load
    document.addEventListener('DOMContentLoaded', function() {
        switchPpeppTab('penetapan');
    });
</script>
@endpush
@endsection
