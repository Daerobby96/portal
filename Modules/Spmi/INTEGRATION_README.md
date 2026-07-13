# SPMI Module Integration System

## Overview
Sistem integrasi data yang menghubungkan modul SPMI dengan modul-modul lain dalam aplikasi SPMI. Sistem ini memungkinkan SPMI untuk mengambil dan menampilkan data dari berbagai modul secara terpusat.

## Komponen Sistem

### 1. ModuleIntegrationService
**Path**: `Modules/Spmi/app/Services/ModuleIntegrationService.php`

Service utama yang menangani pengambilan data dari berbagai modul:

#### Modul yang Terintegrasi:
- **Data Akademik** (`App\Models`)
  - Mahasiswa (total, aktif, per prodi)
  - Prestasi (total, per tingkat)

- **Tridharma** (`Modules\Tridharma\Models`)
  - Penelitian (total, status, sumber dana)
  - Pengabdian (total, status)
  - Publikasi (total, jenis, indexing)
  - HKI (total, jenis, status)

- **Kerjasama** (`Modules\Kerjasama\Models`)
  - Total kerjasama
  - Kerjasama aktif
  - Per jenis dan tingkat

- **Tracer Study** (`Modules\TracerStudy\Models`)
  - Total responden
  - Alumni bekerja
  - Wirausaha
  - Studi lanjut

- **Manajemen Aset** (`Modules\ManajemenAset\Models`)
  - Total aset
  - Per kondisi (baik, rusak, dll)
  - Per kategori

#### Metode Utama:
```php
// Ambil data spesifik modul
$service->getMahasiswaData($periodeId);
$service->getPenelitianData($periodeId);
$service->getKerjasamaData($periodeId);
// dll...

// Ambil semua data sekaligus
$service->getAllIntegratedData($periodeId);
```

#### Fitur:
- ✅ Graceful error handling (try-catch)
- ✅ Check ketersediaan modul (class_exists)
- ✅ Filter berdasarkan periode
- ✅ Return flag `available` untuk indikasi modul tersedia

### 2. IntegrationDashboardController
**Path**: `Modules/Spmi/app/Http/Controllers/IntegrationDashboardController.php`

Controller yang menangani tampilan dashboard dan API integrasi.

#### Endpoints:
- `GET /spmi/integrasi` - Dashboard integrasi lengkap
- `GET /spmi/integrasi/data` - API untuk ambil data (AJAX)
- `GET /spmi/integrasi/widget` - Widget untuk dashboard utama

#### Method:
```php
// Tampilan dashboard utama
public function index(Request $request)

// API untuk AJAX request
public function getData(Request $request)

// Widget kecil untuk dashboard utama
public function widget(Request $request)
```

### 3. Views

#### Dashboard Lengkap
**Path**: `Modules/Spmi/resources/views/integration/dashboard.blade.php`

Tampilan dashboard lengkap dengan:
- Filter periode
- Card per kategori data:
  - Data Akademik (mahasiswa, prestasi)
  - Tridharma (penelitian, pengabdian, publikasi, HKI)
  - Kerjasama & Tracer Study
  - Aset & Sarana Prasarana
- Indikator modul tersedia/tidak tersedia
- Statistik detail per modul

#### Widget
**Path**: `Modules/Spmi/resources/views/integration/widget.blade.php`

Widget ringkas untuk dashboard utama dengan:
- Ringkasan data per modul
- Icon yang representatif
- Link ke dashboard lengkap
- Responsive layout

### 4. Routes
**Path**: `Modules/Spmi/routes/web.php`

```php
Route::middleware('role:super_admin,pimpinan,auditor')->group(function () {
    Route::get('integrasi', [IntegrationDashboardController::class, 'index'])
        ->name('spmi.integration.dashboard');
    Route::get('integrasi/data', [IntegrationDashboardController::class, 'getData'])
        ->name('spmi.integration.data');
    Route::get('integrasi/widget', [IntegrationDashboardController::class, 'widget'])
        ->name('spmi.integration.widget');
});
```

### 5. Navigation
**Path**: `resources/views/layouts/sidebar.blade.php`

Menu navigasi ditambahkan di sidebar bagian "Monitoring & Evaluasi":
```
Monitoring & Evaluasi
├── Indikator SPMI (IKU/IKT)
├── IKU Kemdiktisaintek
├── Monitoring IKU/IKT
├── Evaluasi
└── Integrasi Data Modul ← NEW
```

## Penggunaan

### Menampilkan Dashboard Integrasi
Akses melalui menu sidebar: **Monitoring & Evaluasi > Integrasi Data Modul**

URL: `/spmi/integrasi`

### Menggunakan Service di Code
```php
use Modules\Spmi\Services\ModuleIntegrationService;

$service = new ModuleIntegrationService();

// Ambil data mahasiswa untuk periode tertentu
$mahasiswaData = $service->getMahasiswaData(null, $periodeId);

if ($mahasiswaData['available']) {
    echo "Total Mahasiswa: " . $mahasiswaData['total'];
    echo "Mahasiswa Aktif: " . $mahasiswaData['aktif'];
}

// Ambil semua data
$allData = $service->getAllIntegratedData($periodeId);
```

### AJAX Request
```javascript
// Ambil data spesifik modul
fetch('/spmi/integrasi/data?module=mahasiswa&periode_id=1')
    .then(response => response.json())
    .then(data => {
        console.log(data);
    });

// Ambil semua data
fetch('/spmi/integrasi/data?periode_id=1')
    .then(response => response.json())
    .then(data => {
        console.log(data);
    });
```

### Menampilkan Widget di Dashboard
Di dashboard utama, tambahkan:
```blade
@include('spmi::integration.widget', [
    'data' => $integratedData,
    'periode' => $periode
])
```

## Akses Role
Hanya dapat diakses oleh:
- ✅ Super Admin
- ✅ Pimpinan
- ✅ Auditor

## Error Handling

Service akan mengembalikan data kosong dengan flag `available: false` jika:
- Modul tidak terinstall (class tidak ada)
- Terjadi error saat query database
- Model tidak ditemukan

Contoh response saat modul tidak tersedia:
```php
[
    'total' => 0,
    'aktif' => 0,
    'by_prodi' => [],
    'available' => false,
    'error' => 'Error message if any'
]
```

## Filter Periode

Semua method support filter berdasarkan periode:
- Data akan difilter berdasarkan tahun/tanggal periode
- Jika `periodeId` null, akan menampilkan semua data
- Periode default: periode aktif

## Extensibility

Untuk menambah modul baru:

1. Tambah method di `ModuleIntegrationService.php`:
```php
public function getModulBaruData($periodeId = null)
{
    try {
        $modelClass = '\Modules\ModulBaru\Models\ModelBaru';
        if (!class_exists($modelClass)) {
            return ['total' => 0, 'available' => false];
        }
        
        $query = $modelClass::query();
        // ... query logic
        
        return [
            'total' => $total,
            'available' => true
        ];
    } catch (\Exception $e) {
        return ['total' => 0, 'available' => false];
    }
}
```

2. Update method `getAllIntegratedData()`:
```php
public function getAllIntegratedData($periodeId = null)
{
    return [
        // ... existing modules
        'modul_baru' => $this->getModulBaruData($periodeId),
    ];
}
```

3. Tambah tampilan di dashboard view

## Todo / Future Enhancement

- [ ] Auto-sync scheduler untuk update data berkala
- [ ] Cache data untuk performa
- [ ] Export data integrasi ke Excel/PDF
- [ ] Grafik/chart visualisasi data
- [ ] Notifikasi saat ada modul baru tersedia
- [ ] API documentation dengan Swagger
- [ ] Real-time data update dengan WebSocket
- [ ] Dashboard personalisasi per role

## Notes

- Sistem menggunakan namespace modul langsung, tidak ada alias model
- Semua query dilindungi try-catch untuk mencegah error fatal
- Data difilter berdasarkan periode yang dipilih
- Widget dapat di-embed di dashboard manapun
