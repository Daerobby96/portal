# Modul SDM (Sumber Daya Manusia)

Modul untuk manajemen kepegawaian dan sumber daya manusia pada aplikasi SPMI.

## Fitur Utama

### 1. **Presensi Pegawai**
- Pencatatan kehadiran harian (hadir, izin, sakit, alpa, cuti, dinas luar)
- Jam masuk dan jam keluar
- Lokasi dan foto presensi (GPS tracking)
- Rekap presensi per bulan
- Approval untuk status khusus

### 2. **Manajemen Cuti**
- Pengajuan cuti (tahunan, sakit, melahirkan, besar, alasan penting)
- Upload dokumen pendukung
- Workflow approval/reject
- Tracking jumlah hari cuti
- Histori cuti pegawai

### 3. **Lembur**
- Pengajuan lembur dengan jam mulai & selesai
- Perhitungan otomatis jumlah jam lembur
- Upload dokumen pendukung
- Approval/reject lembur
- Rekap lembur per bulan

### 4. **Penilaian Kinerja**
- Penilaian multi-aspek (disiplin, kinerja, loyalitas, kreativitas, kerjasama)
- Perhitungan otomatis nilai total dan predikat
- Periode penilaian (semester 1, semester 2, tahunan)
- Catatan atasan dan pegawai
- Upload dokumen penilaian
- Workflow submit & verifikasi

### 5. **Surat Tugas**
- Pembuatan surat tugas (dinas luar, perjalanan dinas, pelatihan, seminar)
- Multi-pegawai per surat tugas dengan peran berbeda
- Tracking durasi dan anggaran
- Upload file surat resmi
- Workflow approval dan status selesai

## Struktur Database

### Tabel Presensi
- Kehadiran harian pegawai
- GPS coordinates & foto
- Status kehadiran

### Tabel Cuti
- Pengajuan cuti dengan jenis dan durasi
- Approval workflow
- File pendukung

### Tabel Lembur
- Data lembur pegawai
- Perhitungan jam
- Approval

### Tabel Penilaian Kinerja
- Nilai per aspek
- Predikat otomatis
- Periode tahunan/semester

### Tabel Surat Tugas
- Data surat tugas
- Pivot table untuk multi-pegawai
- Status tracking

## Routes

Semua routes berada di prefix `/sdm` dengan middleware `auth`:

```php
Route::get('/sdm', 'SdmController@index'); // Dashboard
Route::resource('/sdm/presensi', 'PresensiController');
Route::resource('/sdm/cuti', 'CutiController');
Route::resource('/sdm/lembur', 'LemburController');
Route::resource('/sdm/penilaian-kinerja', 'PenilaianKinerjaController');
Route::resource('/sdm/surat-tugas', 'SuratTugasController');
```

## Access Control

Modul ini hanya dapat diakses oleh:
- Super Admin
- Pimpinan

Konfigurasi di sidebar: `@if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())`

## Models

1. **Presensi** - `Modules\Sdm\Models\Presensi`
2. **Cuti** - `Modules\Sdm\Models\Cuti`
3. **Lembur** - `Modules\Sdm\Models\Lembur`
4. **PenilaianKinerja** - `Modules\Sdm\Models\PenilaianKinerja`
5. **SuratTugas** - `Modules\Sdm\Models\SuratTugas`

Semua model menggunakan trait `Loggable` untuk activity logging.

## Integrasi

Modul SDM terintegrasi dengan:
- **DataMaster Module**: Menggunakan model `Pegawai` dari `Modules\DataMaster\Models\Pegawai`
- **User Model**: Untuk approval dan tracking

## Dashboard

Dashboard SDM menampilkan:
- Statistik presensi hari ini
- Cuti pending & aktif
- Total jam lembur bulan ini
- Surat tugas aktif
- Penilaian kinerja tahun berjalan
- Recent activities (cuti, lembur, surat tugas terbaru)

## File Upload

File pendukung disimpan di:
- Cuti: `storage/sdm/cuti/`
- Lembur: `storage/sdm/lembur/`
- Penilaian: `storage/sdm/penilaian/`
- Surat Tugas: `storage/sdm/surat-tugas/`

Max upload: 5MB (PDF, JPG, PNG)

## Status Workflow

### Cuti & Lembur
- `pending` - Menunggu approval
- `approved` - Disetujui
- `rejected` - Ditolak

### Penilaian Kinerja
- `draft` - Draft
- `submitted` - Diajukan
- `verified` - Terverifikasi

### Surat Tugas
- `draft` - Draft
- `pending` - Menunggu approval
- `approved` - Disetujui
- `rejected` - Ditolak
- `selesai` - Selesai

## Todo / Future Enhancement

- [ ] API mobile untuk presensi selfie
- [ ] Notifikasi email untuk approval
- [ ] Export rekap ke Excel
- [ ] Chart analytics kinerja pegawai
- [ ] Integrasi dengan gaji/payroll
- [ ] Dashboard per pegawai (self-service)
- [ ] QR Code untuk presensi
- [ ] Geofencing untuk validasi lokasi presensi

## License

Internal module untuk aplikasi SPMI.
