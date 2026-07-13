# Modul Manajemen Surat

Modul untuk mengelola surat-menyurat di institusi dengan fitur lengkap untuk surat keluar, surat masuk, dan disposisi.

## 🎯 Konsep: Record-Only System (Tidak Menyimpan File)

Sistem ini dirancang untuk **mencatat metadata surat** saja, **TIDAK menyimpan file PDF** di server untuk menghemat storage.

### Keuntungan:
- ✅ **Hemat Storage**: Tidak ada file PDF yang disimpan permanen
- ✅ **Lebih Cepat**: Tidak perlu proses save file
- ✅ **Generate On-Demand**: PDF di-generate saat dibutuhkan (preview/download)
- ✅ **Fokus Pencatatan**: Track nomor surat, tanggal, perihal, tujuan, approval
- ✅ **Pelaporan Lengkap**: Data metadata tetap lengkap untuk reporting

### Alur Kerja:
1. **Input Data**: User input metadata surat (nomor, perihal, isi, dll)
2. **Simpan ke Database**: Hanya data metadata yang disimpan
3. **Generate On-Demand**: 
   - Klik "Preview PDF" → Generate & tampilkan di browser (tidak disimpan)
   - Klik "Download PDF" → Generate & download langsung (tidak disimpan)
4. **Hapus Data**: Hapus record dari database (tidak ada file fisik)

### Exception: Surat Masuk
- Surat masuk **bisa upload file** karena dari eksternal (scan/foto surat fisik)
- File surat masuk disimpan untuk referensi

## Fitur Utama

### 1. Surat Keluar
- **13 Jenis Surat**: SK Yayasan, SK PT, Surat Tugas, Surat Undangan, Surat Keterangan, Surat Edaran, Surat Pengantar, MOU, MOA, Surat Rekomendasi, dan lainnya
- **Auto-numbering**: Penomoran otomatis dengan format XXX/KODE-JENIS/UNIT/MM/YYYY
- **Rich Text Editor (Jodit)**: Editor WYSIWYG untuk membuat isi surat dengan format HTML
- **Template Surat**: Template siap pakai untuk berbagai jenis surat
- **Generate PDF**: Otomatis generate PDF dengan kop surat dari settings
- **Workflow Approval**: Draft → Pending → Approved → Published
- **Kop Surat Dinamis**: Menggunakan kop surat yang sudah diupload di pengaturan

### 2. Surat Masuk
- Register surat masuk dengan file attachment
- Tracking sumber dan tujuan surat
- Disposisi surat masuk ke user terkait

### 3. Disposisi
- Sistem disposisi surat masuk
- Status tracking (pending, in_progress, completed)
- Notifikasi disposisi

### 4. Dashboard
- Statistik surat (keluar, masuk, disposisi)
- Grafik tren 6 bulan terakhir
- Aktivitas terbaru

## Template PDF Surat

Semua template PDF menggunakan kop surat dari database settings:
- `kop_surat_yayasan` - untuk SK Yayasan
- `kop_surat_pt` - untuk semua surat lainnya

### Template yang Tersedia:
1. **sk_yayasan.blade.php** - Surat Keputusan Yayasan
2. **sk_pt.blade.php** - Surat Keputusan Perguruan Tinggi
3. **surat_tugas.blade.php** - Surat Tugas
4. **surat_undangan.blade.php** - Surat Undangan
5. **surat_keterangan.blade.php** - Surat Keterangan
6. **surat_edaran.blade.php** - Surat Edaran
7. **surat_pengantar.blade.php** - Surat Pengantar
8. **mou.blade.php** - Memorandum of Understanding
9. **moa.blade.php** - Memorandum of Agreement
10. **surat_rekomendasi.blade.php** - Surat Rekomendasi
11. **surat_generic.blade.php** - Template generik untuk jenis surat lainnya

## Jodit Editor

Jodit Editor digunakan untuk membuat isi surat dengan fitur:
- Formatting (bold, italic, underline)
- Lists (ordered, unordered)
- Tables
- Alignment
- Font size & family
- Insert template cepat

### Template Cepat Tersedia:
- Template Surat Tugas
- Template Undangan
- Template Keterangan
- Template Edaran

## Routes

### Surat Keluar
```
GET    /surat-keluar              - List surat keluar
GET    /surat-keluar/create       - Form buat surat
POST   /surat-keluar              - Simpan surat baru
GET    /surat-keluar/{id}         - Detail surat
GET    /surat-keluar/{id}/edit    - Form edit surat
PUT    /surat-keluar/{id}         - Update surat
DELETE /surat-keluar/{id}         - Hapus surat
GET    /surat-keluar/{id}/pdf     - Download PDF
GET    /surat-keluar/{id}/preview-pdf - Preview PDF di browser
POST   /surat-keluar/{id}/approve - Approve surat
POST   /surat-keluar/{id}/reject  - Reject surat
```

### Surat Masuk
```
GET    /surat-masuk               - List surat masuk
GET    /surat-masuk/create        - Form register surat masuk
POST   /surat-masuk               - Simpan surat masuk
GET    /surat-masuk/{id}          - Detail surat masuk
GET    /surat-masuk/{id}/edit     - Form edit
PUT    /surat-masuk/{id}          - Update surat
DELETE /surat-masuk/{id}          - Hapus surat
```

### Disposisi
```
GET    /disposisi/my-disposisi    - Disposisi yang saya terima
GET    /surat-masuk/{id}/disposisi/create - Form disposisi
POST   /surat-masuk/{id}/disposisi - Buat disposisi
GET    /disposisi/{id}            - Detail disposisi
POST   /disposisi/{id}/update-status - Update status disposisi
```

## Database Tables

1. **jenis_surat** - Master jenis surat
2. **nomor_surat** - Tracking penomoran otomatis
3. **surat_keluar** - Data surat keluar
4. **surat_masuk** - Data surat masuk
5. **disposisi** - Data disposisi

## Settings Required

Pastikan settings berikut sudah diisi di database:
- `kop_surat_yayasan` - Path ke file kop surat yayasan
- `kop_surat_pt` - Path ke file kop surat perguruan tinggi
- `nama_institusi` - Nama institusi
- `alamat_institusi` - Alamat lengkap
- `kota_institusi` - Nama kota

## Installation

Module sudah aktif. Jika ada masalah:

```bash
# Enable module
php artisan module:enable ManajemenSurat

# Run migrations
php artisan migrate --path=Modules/ManajemenSurat/database/migrations

# Seed jenis surat
php artisan module:seed ManajemenSurat

# Clear cache
php artisan optimize:clear
```

## Roles & Permissions

Access control berdasarkan role:
- `super_admin`, `pimpinan` - Full access + approval
- `admin_prodi`, `staff` - CRUD surat keluar & masuk
- All authenticated users - Lihat disposisi mereka

## Dependencies

- Laravel 12
- barryvdh/laravel-dompdf (PDF generation)
- Jodit Editor (Rich text editor)

## Cara Penggunaan

### Membuat Surat Keluar

1. Klik "Buat Surat Baru"
2. Pilih jenis surat
3. Isi form (nomor surat otomatis)
4. Gunakan Jodit Editor untuk isi surat
   - Klik tombol template untuk insert template cepat
   - Customize sesuai kebutuhan
5. Simpan sebagai Draft/Pending/Published
6. Download PDF dengan kop surat otomatis

### Generate PDF

PDF otomatis menggunakan:
- Kop surat dari settings database
- Template sesuai jenis surat
- Format profesional dengan tanda tangan
- Auto-fallback ke template generic jika template belum ada

## Support

Untuk pertanyaan atau issue, hubungi tim developer.
