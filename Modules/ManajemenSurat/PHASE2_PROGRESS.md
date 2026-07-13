# Phase 2 Implementation Progress - Core Features

## 📋 Overview
Phase 2 focuses on building the core features including Views, PDF Templates, Notifications, and Dashboard.

---

## ✅ Completed

### 1. Module Structure Updates
- ✅ Updated namespace dari `suratkeputusan::` → `manajemen-surat::`
- ✅ Updated sidebar navigation dengan menu lengkap
- ✅ Created folder structure untuk views:
  - `resources/views/surat-keputusan/`
  - `resources/views/surat-keluar/`
  - `resources/views/surat-masuk/`
  - `resources/views/disposisi/`
  - `resources/views/dashboard/`

### 2. Sidebar Navigation
Enhanced sidebar dengan menu:
- Dashboard
- Surat Keluar (All types)
- Surat Keputusan (SK Yayasan & PT)
- Surat Masuk
- Disposisi Saya (dengan badge counter)

---

## 🚧 In Progress

### Views to Create

#### Dashboard (`dashboard/index.blade.php`)
- [ ] Statistics cards
- [ ] Recent surat masuk/keluar
- [ ] Pending approvals
- [ ] Disposisi alerts
- [ ] Charts & graphs

#### Surat Keluar
- [ ] `index.blade.php` - List dengan filter
- [ ] `create.blade.php` - Form create
- [ ] `edit.blade.php` - Form edit
- [ ] `show.blade.php` - Detail view
- [ ] `_form.blade.php` - Reusable form component

#### Surat Masuk  
- [ ] `index.blade.php` - List dengan filter
- [ ] `create.blade.php` - Form create dengan upload
- [ ] `edit.blade.php` - Form edit
- [ ] `show.blade.php` - Detail + disposisi list
- [ ] `_form.blade.php` - Reusable form component

#### Disposisi
- [ ] `my-disposisi.blade.php` - List disposisi user
- [ ] `show.blade.php` - Detail disposisi
- [ ] `create.blade.php` - Form disposisi (from surat masuk)
- [ ] `_status-update.blade.php` - Component update status

#### PDF Templates
- [ ] `pdf/surat_tugas.blade.php`
- [ ] `pdf/surat_undangan.blade.php`
- [ ] `pdf/surat_keterangan.blade.php`
- [ ] `pdf/surat_edaran.blade.php`
- [ ] `pdf/surat_pengantar.blade.php`
- [ ] Update existing SK templates

---

## 📝 Next Steps

### Priority 1: Critical Views
1. Dashboard with statistics
2. Surat Keluar index & create
3. Surat Masuk index & create
4. Disposisi my-disposisi

### Priority 2: Notifications
1. Create Notification model & migration
2. Email notifications for disposisi
3. In-app notifications
4. Real-time alerts

### Priority 3: PDF Templates
1. Generic template base
2. Templates for each jenis surat
3. Watermark & branding
4. QR code for verification

### Priority 4: Dashboard & Analytics
1. Statistics calculation
2. Charts integration (Chart.js)
3. Export functionality
4. Date range filters

---

## 🔧 Technical Notes

### Blade Components to Create
```
components/
├── surat-card.blade.php        - Card component untuk surat
├── status-badge.blade.php      - Badge untuk status
├── filter-form.blade.php       - Reusable filter form
└── stats-card.blade.php        - Statistics card component
```

### JavaScript Dependencies
- Chart.js for dashboard charts
- Select2 for better dropdowns
- Flatpickr for date pickers
- SweetAlert2 for confirmations

### CSS Utilities
Already using Tailwind-like classes, continue with:
- `rounded-2xl`, `shadow-sm`
- `bg-blue-600`, `text-white`
- `hover:bg-blue-700`, `transition-all`

---

## 📊 View Structure Template

### Standard Index View Structure:
```blade
@extends('manajemen-surat::layouts.master')

@section('title', 'Page Title')
@section('page-title', 'Page Title')
@section('page-subtitle', 'Subtitle')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Current Page</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Stats Cards --}}
    
    {{-- Filters --}}
    
    {{-- Table/List --}}
    
    {{-- Pagination --}}
</div>
@endsection
```

### Standard Form Structure:
```blade
@extends('manajemen-surat::layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Title</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('...') }}">
                    @csrf
                    
                    {{-- Form fields --}}
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('...') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 🎨 Design System

### Color Palette
```css
Primary (Blue):   bg-blue-600, text-blue-600
Success (Green):  bg-emerald-600, text-emerald-600
Warning (Yellow): bg-amber-600, text-amber-600
Danger (Red):     bg-rose-600, text-rose-600
Purple:           bg-purple-600, text-purple-600
Slate (Gray):     bg-slate-600, text-slate-600
```

### Status Badges
- **Draft**: `bg-slate-50 text-slate-600`
- **Pending**: `bg-amber-50 text-amber-600`
- **Approved**: `bg-emerald-50 text-emerald-600`
- **Published**: `bg-blue-50 text-blue-600`
- **Rejected**: `bg-rose-50 text-rose-600`

### Icons (Bootstrap Icons)
- Surat Keluar: `bi-box-arrow-up-right`
- Surat Masuk: `bi-box-arrow-in-down-left`
- Disposisi: `bi-person-badge`
- Download: `bi-download`
- Edit: `bi-pencil-square`
- Delete: `bi-trash`
- View: `bi-eye`
- Add: `bi-plus-lg`

---

## 📦 Controllers Enhancement

### Add Dashboard Controller

```php
namespace Modules\ManajemenSurat\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_surat_keluar' => SuratKeluar::count(),
            'total_surat_masuk' => SuratMasuk::count(),
            'pending_approval' => SuratKeluar::status('pending')->count(),
            'disposisi_pending' => Disposisi::untukUser(auth()->id())->pending()->count(),
            'disposisi_overdue' => Disposisi::untukUser(auth()->id())->overdue()->count(),
        ];
        
        $recentSuratMasuk = SuratMasuk::latest()->take(5)->get();
        $recentSuratKeluar = SuratKeluar::latest()->take(5)->get();
        $myDisposisi = Disposisi::untukUser(auth()->id())->latest()->take(5)->get();
        
        return view('manajemen-surat::dashboard.index', compact('stats', 'recentSuratMasuk', 'recentSuratKeluar', 'myDisposisi'));
    }
}
```

### Add Route for Dashboard

```php
Route::get('/manajemen-surat/dashboard', [DashboardController::class, 'index'])
    ->name('manajemen-surat.dashboard');
```

---

## 🔔 Notification System Plan

### Notification Events
1. **Surat Masuk Baru** → Notify admin/staff
2. **Disposisi Dibuat** → Notify penerima disposisi
3. **Disposisi Deadline** → Remind 1 day before
4. **Surat Perlu Approval** → Notify approver
5. **Surat Approved/Rejected** → Notify creator

### Notification Channels
- Database (in-app)
- Email
- (Future: SMS, WhatsApp)

### Notification Model
```php
// Already exists in Laravel
use Illuminate\Notifications\Notification;
```

---

## 📚 Documentation Needs

- [ ] User Guide for each module
- [ ] API Documentation (Phase 3)
- [ ] Admin Guide for configuration
- [ ] Troubleshooting Guide

---

**Status: Phase 2 - IN PROGRESS 🚧**  
**Completion: ~30%**  
**Next: Create critical views**

