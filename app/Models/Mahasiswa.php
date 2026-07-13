<?php

namespace App\Models;

use Modules\DataMaster\Models\ProgramStudi;

use Modules\DataMaster\Models\Periode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nim', 'nama', 'jenis_kelamin', 'no_hp', 'email',
        'prodi_id', 'periode_id',
        'angkatan', 'semester_berjalan', 'jalur_masuk', 'ipk',
        'status', 'tanggal_masuk', 'tanggal_lulus', 'masa_studi_bulan',
        'keterangan',
        // Akademik Tambahan
        'sistem_kuliah', 'gelombang_daftar', 'is_transfer', 'universitas_asal', 'nim_asal', 'ipk_asal', 'kurikulum',
        // Biodata Pribadi
        'agama', 'kewarganegaraan', 'alamat', 'telepon', 'tempat_lahir', 'tanggal_lahir', 'kodepos', 'golongan_darah', 'status_nikah', 'nik', 'no_kk',
        'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'tgl_daftar',
        // Data Ayah
        'nama_ayah', 'alamat_ayah', 'telp_ayah', 'tgl_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah',
        // Data Ibu
        'nama_ibu', 'alamat_ibu', 'telp_ibu', 'tgl_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
        // Data Wali
        'nama_wali', 'alamat_wali', 'telp_wali', 'tgl_lahir_wali', 'pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali'
    ];

    protected $casts = [
        'ipk'             => 'decimal:2',
        'tanggal_masuk'   => 'date',
        'tanggal_lulus'   => 'date',
        'tanggal_lahir'   => 'date',
        'tgl_daftar'      => 'date',
        'angkatan'        => 'integer',
        'semester_berjalan' => 'integer',
        'masa_studi_bulan'  => 'integer',
    ];

    // ─── Constants ────────────────────────────────────────────────
    const STATUS_AKTIF             = 'aktif';
    const STATUS_LULUS             = 'lulus';
    const STATUS_CUTI              = 'cuti';
    const STATUS_DO                = 'DO';
    const STATUS_MENGUNDURKAN_DIRI = 'mengundurkan_diri';

    const JALUR_MASUK = ['SNBP', 'SNBT', 'Mandiri', 'Beasiswa', 'Perpindahan', 'Lainnya'];

    public static function statusOptions(): array
    {
        return [
            self::STATUS_AKTIF             => 'Aktif',
            self::STATUS_LULUS             => 'Lulus',
            self::STATUS_CUTI              => 'Cuti',
            self::STATUS_DO                => 'Drop Out (DO)',
            self::STATUS_MENGUNDURKAN_DIRI => 'Mengundurkan Diri',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function scopeLulus($query)
    {
        return $query->where('status', self::STATUS_LULUS);
    }

    public function scopeByAngkatan($query, int $angkatan)
    {
        return $query->where('angkatan', $angkatan);
    }

    public function scopeByProdi($query, int $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_AKTIF             => '<span class="badge bg-success">Aktif</span>',
            self::STATUS_LULUS             => '<span class="badge bg-primary">Lulus</span>',
            self::STATUS_CUTI              => '<span class="badge bg-warning text-dark">Cuti</span>',
            self::STATUS_DO                => '<span class="badge bg-danger">DO</span>',
            self::STATUS_MENGUNDURKAN_DIRI => '<span class="badge bg-secondary">Undur Diri</span>',
            default                        => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }

    public function getInisialAttribute(): string
    {
        $parts = explode(' ', trim($this->nama));
        $init  = strtoupper(substr($parts[0], 0, 1));
        if (count($parts) > 1) {
            $init .= strtoupper(substr(end($parts), 0, 1));
        }
        return $init;
    }

    public function getMasaStudiTahunAttribute(): ?string
    {
        if (!$this->masa_studi_bulan) return null;
        $tahun  = intdiv($this->masa_studi_bulan, 12);
        $bulan  = $this->masa_studi_bulan % 12;
        $result = '';
        if ($tahun) $result .= "{$tahun} Tahun ";
        if ($bulan) $result .= "{$bulan} Bulan";
        return trim($result) ?: null;
    }

    public static function hitungMasaStudi($tanggalMasuk, $tanggalLulus): ?int
    {
        if (!$tanggalMasuk || !$tanggalLulus) return null;
        $masuk = \Carbon\Carbon::parse($tanggalMasuk);
        $lulus = \Carbon\Carbon::parse($tanggalLulus);
        return max(0, $masuk->diffInMonths($lulus));
    }

    /**
     * Hitung semester berjalan otomatis berdasarkan tahun angkatan
     * dikomparasi dengan Tahun Akademik (Periode) yang sedang aktif di sistem.
     */
    public static function hitungSemester(?int $angkatan): ?int
    {
        if (!$angkatan) return null;
        
        // Ambil periode aktif dari database
        $periodeAktif = \Modules\DataMaster\Models\Periode::aktif();
        
        // Jika belum ada periode aktif yang di-set di sistem, fallback ke perhitungan waktu riil
        if (!$periodeAktif) {
            $currentYear = (int) date('Y');
            $currentMonth = (int) date('n');
            
            $diffYear = $currentYear - $angkatan;
            if ($diffYear < 0) return 1;
            
            $semester = $diffYear * 2;
            if ($currentMonth >= 8) {
                $semester += 1;
            }
            
            return max(1, $semester);
        }
        
        // Perhitungan berdasarkan Periode Aktif
        $tahunAktif = (int) $periodeAktif->tahun;
        $semesterAktif = strtolower($periodeAktif->semester); // 'ganjil' atau 'genap'
        
        $diffYear = $tahunAktif - $angkatan;
        if ($diffYear < 0) return 1;
        
        // Default ke semester ganjil tahun tersebut (+1)
        $semester = ($diffYear * 2) + 1; 
        
        // Jika periode aktif adalah genap, tambah 1
        if ($semesterAktif === 'genap') {
            $semester += 1;
        }
        
        return max(1, $semester);
    }
}

