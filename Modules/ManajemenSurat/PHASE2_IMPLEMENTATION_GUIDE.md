# Phase 2 Implementation Guide - Manajemen Surat

## 🎯 Goals
Build the complete user interface, PDF templates, notification system, and dashboard for the Manajemen Surat module.

---

## ✅ Phase 2 Components

### 1. Dashboard ✅
- **Controller**: `DashboardController.php` ✅
- **Route**: `/manajemen-surat` ✅
- **View**: Need to create `dashboard/index.blade.php`

**Features**:
- Statistics cards (total surat, pending, disposisi)
- Chart 6 bulan terakhir
- Recent surat masuk/keluar
- Pending approvals (for admin)
- My disposisi alerts

### 2. Views Structure

```
resources/views/
├── layouts/
│   ├── master.blade.php ✅
│   └── sidebar.blade.php ✅ (Updated)
├── dashboard/
│   └── index.blade.php ⏳
├── surat-keputusan/
│   ├── index.blade.php ✅
│   └── create.blade.php ✅
├── surat-keluar/
│   ├── index.blade.php ⏳
│   ├── create.blade.php ⏳
│   ├── edit.blade.php ⏳
│   ├── show.blade.php ⏳
│   └── _form.blade.php ⏳
├── surat-masuk/
│   ├── index.blade.php ⏳
│   ├── create.blade.php ⏳
│   ├── edit.blade.php ⏳
│   ├── show.blade.php ⏳
│   └── _form.blade.php ⏳
├── disposisi/
│   ├── my-disposisi.blade.php ⏳
│   ├── show.blade.php ⏳
│   └── create.blade.php ⏳
└── pdf/
    ├── sk_yayasan.blade.php ✅
    ├── sk_pt.blade.php ✅
    ├── surat_tugas.blade.php ⏳
    ├── surat_undangan.blade.php ⏳
    ├── surat_keterangan.blade.php ⏳
    ├── surat_edaran.blade.php ⏳
    └── surat_pengantar.blade.php ⏳
```

---

## 📝 View Templates to Create

### Dashboard View (`dashboard/index.blade.php`)

```blade
@extends('manajemen-surat::layouts.master')

@section('title', 'Dashboard Manajemen Surat')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview sistem manajemen surat')

@section('content')
<div class="row g-4">
    {{-- Statistics Cards --}}
    <div class="col-12">
        <div class="row g-3">
            {{-- Surat Keluar Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-blue-600 to-blue-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-box-arrow-up-right fs-3"></i>
                            </div>
                            <div>
                                <div class="text-blue-100 text-xs font-semibold">Surat Keluar</div>
                                <div class="text-white text-2xl font-black">{{ $stats['total_surat_keluar'] }}</div>
                                <div class="text-blue-200 text-xs">{{ $stats['total_surat_keluar_bulan_ini'] }} bulan ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Surat Masuk Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-emerald-600 to-emerald-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-box-arrow-in-down-left fs-3"></i>
                            </div>
                            <div>
                                <div class="text-emerald-100 text-xs font-semibold">Surat Masuk</div>
                                <div class="text-white text-2xl font-black">{{ $stats['total_surat_masuk'] }}</div>
                                <div class="text-emerald-200 text-xs">{{ $stats['surat_masuk_baru'] }} surat baru</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Approval Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-amber-600 to-amber-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-clock-history fs-3"></i>
                            </div>
                            <div>
                                <div class="text-amber-100 text-xs font-semibold">Pending Approval</div>
                                <div class="text-white text-2xl font-black">{{ $stats['pending_approval'] }}</div>
                                <div class="text-amber-200 text-xs">Perlu persetujuan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- My Disposisi Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-purple-600 to-purple-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-person-badge fs-3"></i>
                            </div>
                            <div>
                                <div class="text-purple-100 text-xs font-semibold">Disposisi Saya</div>
                                <div class="text-white text-2xl font-black">{{ $stats['my_disposisi_pending'] }}</div>
                                <div class="text-purple-200 text-xs">{{ $stats['my_disposisi_overdue'] }} overdue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="col-md-8">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-0 p-4">
                <h6 class="mb-0 font-bold">Tren Surat 6 Bulan Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="chartSurat" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- My Disposisi Alerts --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-bold">Disposisi Saya</h6>
                <a href="{{ route('disposisi.my-disposisi') }}" class="text-xs text-blue-600 font-semibold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($myDisposisi as $disposisi)
                <div class="p-3 border-b border-slate-100 hover:bg-slate-50">
                    <div class="d-flex gap-2">
                        @if($disposisi->prioritas === 'tinggi')
                        <div class="h-2 w-2 rounded-full bg-rose-500 mt-1.5"></div>
                        @elseif($disposisi->prioritas === 'sedang')
                        <div class="h-2 w-2 rounded-full bg-amber-500 mt-1.5"></div>
                        @else
                        <div class="h-2 w-2 rounded-full bg-slate-400 mt-1.5"></div>
                        @endif
                        <div class="flex-grow-1">
                            <a href="{{ route('disposisi.show', $disposisi) }}" class="text-sm font-semibold text-slate-800 hover:text-blue-600">
                                {{ Str::limit($disposisi->suratMasuk->perihal, 50) }}
                            </a>
                            <div class="text-xs text-slate-400 mt-0.5">
                                Dari: {{ $disposisi->dari->name }}
                            </div>
                            @if($disposisi->batas_waktu)
                            <div class="text-xs text-slate-500 mt-1">
                                <i class="bi bi-clock"></i> {{ $disposisi->batas_waktu->format('d M Y') }}
                                @if($disposisi->isOverdue())
                                <span class="text-rose-600 font-semibold">(Overdue)</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-slate-400 text-sm">
                    Tidak ada disposisi aktif
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Surat Masuk --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-bold">Surat Masuk Terbaru</h6>
                <a href="{{ route('surat-masuk.index') }}" class="text-xs text-blue-600 font-semibold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @forelse($recentSuratMasuk as $surat)
                            <tr class="border-b border-slate-100">
                                <td class="py-2.5 px-3">
                                    <div class="text-sm font-semibold text-slate-800">{{ $surat->nomor_agenda }}</div>
                                    <div class="text-xs text-slate-400">{{ $surat->pengirim }}</div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="text-sm text-slate-600">{{ Str::limit($surat->perihal, 40) }}</div>
                                </td>
                                <td class="py-2.5 px-3 text-end">
                                    <div class="text-xs text-slate-400">{{ $surat->tanggal_terima->format('d M') }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center text-slate-400 text-sm">Belum ada surat masuk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Surat Keluar --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-bold">Surat Keluar Terbaru</h6>
                <a href="{{ route('surat-keluar.index') }}" class="text-xs text-blue-600 font-semibold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @forelse($recentSuratKeluar as $surat)
                            <tr class="border-b border-slate-100">
                                <td class="py-2.5 px-3">
                                    <div class="text-sm font-semibold text-slate-800">{{ $surat->nomor_surat }}</div>
                                    <div class="text-xs text-slate-400">{{ $surat->jenisSurat->nama }}</div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="text-sm text-slate-600">{{ Str::limit($surat->perihal, 40) }}</div>
                                </td>
                                <td class="py-2.5 px-3 text-end">
                                    <div class="text-xs text-slate-400">{{ $surat->tanggal_surat->format('d M') }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center text-slate-400 text-sm">Belum ada surat keluar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartSurat');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
            {
                label: 'Surat Masuk',
                data: {!! json_encode($chartData['surat_masuk']) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4
            },
            {
                label: 'Surat Keluar',
                data: {!! json_encode($chartData['surat_keluar']) !!},
                borderColor: 'rgb(37, 99, 235)',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>
@endpush
@endsection
```

---

## 🚀 Quick Start untuk Developer

### 1. Create All Views

Run these commands to create view files:

```bash
# Dashboard
php artisan make:view ManajemenSurat::dashboard.index

# Surat Keluar
php artisan make:view ManajemenSurat::surat-keluar.index
php artisan make:view ManajemenSurat::surat-keluar.create
php artisan make:view ManajemenSurat::surat-keluar.edit
php artisan make:view ManajemenSurat::surat-keluar.show

# Surat Masuk  
php artisan make:view ManajemenSurat::surat-masuk.index
php artisan make:view ManajemenSurat::surat-masuk.create
php artisan make:view ManajemenSurat::surat-masuk.edit
php artisan make:view ManajemenSurat::surat-masuk.show

# Disposisi
php artisan make:view ManajemenSurat::disposisi.my-disposisi
php artisan make:view ManajemenSurat::disposisi.show
php artisan make:view ManajemenSurat::disposisi.create
```

### 2. Copy Template Structure

Each view should follow this structure:
- Extend `manajemen-surat::layouts.master`
- Set title, page-title, page-subtitle
- Add breadcrumb navigation
- Use consistent styling (Tailwind-like utilities)

### 3. Testing Checklist

- [ ] Dashboard loads with correct statistics
- [ ] Charts display properly
- [ ] All surat keluar CRUD operations work
- [ ] All surat masuk CRUD operations work
- [ ] File upload works for surat masuk
- [ ] Disposisi creation works
- [ ] Disposisi status updates work
- [ ] Filters and search work
- [ ] Pagination works
- [ ] PDF generation works

---

## 📊 Database Ready?

Before testing views, ensure:

```bash
# 1. Database is running
# 2. Run migrations
php artisan module:migrate ManajemenSurat

# 3. Seed master data
php artisan module:seed ManajemenSurat

# 4. Check tables
php artisan tinker
>>> \Modules\ManajemenSurat\Models\JenisSurat::count()
>>> \Modules\ManajemenSurat\Models\SuratKeluar::count()
```

---

## 🎨 Component Library

Reusable components to create:

### Status Badge Component
```blade
{{-- components/status-badge.blade.php --}}
@props(['status'])

@php
$colors = [
    'draft' => 'bg-slate-50 text-slate-600 border-slate-200',
    'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
    'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
    'published' => 'bg-blue-50 text-blue-600 border-blue-200',
    'rejected' => 'bg-rose-50 text-rose-600 border-rose-200',
];
$color = $colors[$status] ?? 'bg-slate-50 text-slate-600';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold {{ $color }}">
    {{ ucfirst($status) }}
</span>
```

### Stat Card Component
```blade
{{-- components/stat-card.blade.php --}}
@props(['title', 'value', 'subtitle' => '', 'icon', 'color' => 'blue'])

<div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-{{ $color }}-600 to-{{ $color }}-700">
    <div class="card-body p-4 text-white">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-xl bg-white/10 p-3">
                <i class="bi bi-{{ $icon }} fs-3"></i>
            </div>
            <div>
                <div class="text-{{ $color }}-100 text-xs font-semibold">{{ $title }}</div>
                <div class="text-white text-2xl font-black">{{ $value }}</div>
                @if($subtitle)
                <div class="text-{{ $color }}-200 text-xs">{{ $subtitle }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
```

---

## 📋 Next Actions

1. **Create dashboard view** using template above
2. **Create surat-keluar views** (index, create, edit, show)
3. **Create surat-masuk views** (index, create, edit, show)
4. **Create disposisi views** (my-disposisi, show, create)
5. **Test all features** with real data
6. **Create PDF templates** for new surat types
7. **Implement notifications** (Phase 2 Part 2)

---

## 🐛 Common Issues & Solutions

### Issue: Namespace error in views
**Solution**: Use `manajemen-surat::` not `suratkeputusan::`

### Issue: Route not found
**Solution**: Run `php artisan route:clear`

### Issue: Class not found
**Solution**: Run `composer dump-autoload`

### Issue: View not found
**Solution**: Check view path matches namespace

---

**Status: Phase 2 - Core Views Setup Complete ✅**  
**Next: Create actual view files and test**  
**Date: July 8, 2026**

