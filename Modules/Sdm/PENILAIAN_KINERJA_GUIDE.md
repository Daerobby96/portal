# Panduan Penilaian Kinerja - Module SDM

## Cara Menambahkan Penilaian Kinerja

### 1. Akses Menu Penilaian Kinerja

1. Login ke aplikasi SPMI
2. Buka **Module SDM** dari dashboard utama
3. Di sidebar kiri, cari bagian **"Penilaian"**
4. Klik menu **"Penilaian Kinerja"**
5. Anda akan diarahkan ke halaman daftar penilaian kinerja

**URL Akses:**
```
http://your-domain/sdm/penilaian-kinerja
```

### 2. Membuat Penilaian Baru

1. Di halaman **Penilaian Kinerja**, klik tombol **"Buat Penilaian"** (pojok kanan atas)
2. Anda akan diarahkan ke form pengisian penilaian

### 3. Mengisi Form Penilaian

#### A. Data Dasar
- **Pegawai yang Dinilai**: Pilih pegawai dari dropdown (wajib)
- **Tahun**: Pilih tahun penilaian (wajib)
- **Periode**: Pilih periode penilaian (wajib)
  - Semester 1
  - Semester 2
  - Tahunan

#### B. Aspek Penilaian (Skala 0-100)

Berikan nilai untuk setiap aspek berikut:

1. **Kedisiplinan** (0-100)
   - Kehadiran
   - Ketepatan waktu
   - Kepatuhan terhadap aturan

2. **Kinerja** (0-100)
   - Pencapaian target
   - Kualitas kerja
   - Produktivitas

3. **Loyalitas** (0-100)
   - Komitmen
   - Dedikasi
   - Integritas

4. **Kreativitas** (0-100)
   - Inovasi
   - Inisiatif
   - Pemecahan masalah

5. **Kerjasama** (0-100)
   - Teamwork
   - Komunikasi
   - Kolaborasi

#### C. Data Pendukung (Opsional)
- **Catatan Atasan**: Tambahkan komentar atau evaluasi
- **Dokumen Pendukung**: Upload file PDF (maksimal 5MB)

### 4. Menyimpan Penilaian

Setelah mengisi semua data:
- Klik tombol **"Simpan Penilaian"** untuk menyimpan
- Penilaian akan tersimpan dengan status **"Draft"**

### 5. Perhitungan Otomatis

Sistem akan secara otomatis menghitung:

**Nilai Total** = (Kedisiplinan + Kinerja + Loyalitas + Kreativitas + Kerjasama) / 5

**Predikat** ditentukan berdasarkan nilai total:
- **≥ 90**: Sangat Baik
- **80-89**: Baik
- **70-79**: Cukup
- **< 70**: Kurang

### 6. Status Penilaian

Penilaian memiliki 3 status:

1. **Draft**: Penilaian baru dibuat, masih bisa diedit
2. **Submitted**: Penilaian sudah diajukan (klik tombol Submit)
3. **Verified**: Penilaian sudah diverifikasi oleh admin

### 7. Mengelola Penilaian

#### Melihat Detail
- Klik ikon **mata (👁)** pada kolom Aksi
- Lihat detail lengkap penilaian dengan grafik predikat

#### Mengedit Penilaian
- Klik ikon **pensil (✏)** pada kolom Aksi
- Hanya penilaian dengan status **Draft** atau **Submitted** yang bisa diedit
- Penilaian **Verified** tidak bisa diedit

#### Menghapus Penilaian
- Klik ikon **tempat sampah (🗑)** pada kolom Aksi
- Konfirmasi penghapusan
- Hanya penilaian dengan status **Draft** yang bisa dihapus

#### Submit Penilaian
- Di halaman detail, klik tombol **"Submit"**
- Penilaian akan berubah status menjadi **Submitted**

#### Verifikasi Penilaian (Admin)
- Di halaman detail, klik tombol **"Verify"**
- Penilaian akan berubah status menjadi **Verified**
- Setelah verified, penilaian tidak bisa diedit lagi

### 8. Fitur Filter dan Pencarian

Di halaman daftar penilaian, Anda bisa:
- **Filter Tahun**: Pilih tahun tertentu
- **Filter Periode**: Pilih semester 1, semester 2, atau tahunan
- **Pagination**: Navigasi antar halaman (20 data per halaman)

### 9. Dashboard & Statistik

Di bagian atas halaman index, terdapat 4 card statistik:
1. **Total Penilaian**: Jumlah semua penilaian
2. **Tahun Ini**: Jumlah penilaian tahun berjalan
3. **Sangat Baik**: Jumlah penilaian dengan predikat Sangat Baik
4. **Rata-rata Nilai**: Nilai rata-rata tahun berjalan

## Contoh Pengisian

### Contoh Penilaian Pegawai Baik

```
Pegawai: Ahmad Fauzi (NIP: 19850101)
Tahun: 2026
Periode: Semester 1

Aspek Penilaian:
- Kedisiplinan: 85
- Kinerja: 88
- Loyalitas: 82
- Kreativitas: 80
- Kerjasama: 87

Nilai Total: 84.40 → Predikat: BAIK

Catatan Atasan:
"Pegawai memiliki kinerja yang konsisten dan baik dalam kerjasama tim. 
Perlu peningkatan dalam aspek kreativitas dan inovasi."
```

### Contoh Penilaian Pegawai Sangat Baik

```
Pegawai: Siti Nurhaliza (NIP: 19900202)
Tahun: 2026
Periode: Tahunan

Aspek Penilaian:
- Kedisiplinan: 95
- Kinerja: 92
- Loyalitas: 93
- Kreativitas: 88
- Kerjasama: 94

Nilai Total: 92.40 → Predikat: SANGAT BAIK

Catatan Atasan:
"Pegawai telah menunjukkan kinerja yang sangat baik sepanjang tahun. 
Konsisten dalam mencapai target dan aktif dalam berbagi pengetahuan dengan tim."
```

## Troubleshooting

### Error: "Penilaian untuk pegawai, tahun, dan periode ini sudah ada"
**Solusi**: Setiap pegawai hanya bisa memiliki 1 penilaian per tahun dan periode. Cek kembali atau edit penilaian yang sudah ada.

### Error: "Undefined variable $pegawais"
**Solusi**: Pastikan controller mengirimkan data pegawai. Hubungi administrator.

### File upload gagal
**Solusi**: 
- Pastikan file berformat PDF
- Ukuran maksimal 5MB
- Pastikan folder storage/app/public/sdm/penilaian memiliki permission yang benar

### Menu tidak muncul
**Solusi**:
- Pastikan Anda sudah login
- Pastikan role Anda memiliki akses ke Module SDM
- Clear cache browser

## Tips Penggunaan

1. **Buat Draft Terlebih Dahulu**
   - Isi data secara bertahap
   - Simpan sebagai draft
   - Review sebelum submit

2. **Gunakan Catatan**
   - Berikan feedback konstruktif
   - Jelaskan alasan nilai yang diberikan
   - Sertakan contoh konkret

3. **Upload Dokumen**
   - Lampirkan bukti pendukung
   - Dokumentasi pencapaian
   - Form evaluasi tambahan

4. **Konsistensi Penilaian**
   - Gunakan standar yang sama untuk semua pegawai
   - Dokumentasikan kriteria penilaian
   - Lakukan kalibrasi antar penilai

5. **Follow-up**
   - Diskusikan hasil dengan pegawai
   - Buat rencana pengembangan
   - Monitor progress

## Catatan Penting

⚠️ **Perhatian:**
- Penilaian yang sudah **Verified** tidak bisa diubah
- Pastikan data sudah benar sebelum verifikasi
- Simpan bukti penilaian untuk arsip
- Lakukan backup data secara berkala

📌 **Best Practice:**
- Lakukan penilaian secara berkala (setiap semester)
- Libatkan pegawai dalam proses evaluasi
- Dokumentasikan semua penilaian dengan baik
- Gunakan hasil penilaian untuk pengembangan pegawai

## Kontak Support

Jika mengalami kendala:
- Hubungi Administrator Sistem
- Buka tiket support
- Dokumentasikan error yang terjadi (screenshot)
