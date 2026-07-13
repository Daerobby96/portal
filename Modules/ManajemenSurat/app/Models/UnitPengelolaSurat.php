<?php

namespace Modules\ManajemenSurat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPengelolaSurat extends Model
{
    protected $table = 'unit_pengelola_surat';

    protected $fillable = [
        'nama',
        'kode',
        'jenis_institusi',
        'prefix_format',
        'deskripsi',
        'pic_nama',
        'pic_jabatan',
        'pic_nip',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ═══════════════════════════════════════════════════════════
    // Relationships
    // ═══════════════════════════════════════════════════════════

    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'unit_id');
    }

    public function nomorSurat(): HasMany
    {
        return $this->hasMany(NomorSurat::class, 'unit_id');
    }

    // ═══════════════════════════════════════════════════════════
    // Scopes
    // ═══════════════════════════════════════════════════════════

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeJenisInstitusi($query, $jenis)
    {
        return $query->where('jenis_institusi', $jenis);
    }

    // ═══════════════════════════════════════════════════════════
    // Accessors
    // ═══════════════════════════════════════════════════════════

    public function getJenisInstitusiLabelAttribute()
    {
        $labels = [
            'yayasan' => 'Yayasan',
            'perguruan_tinggi' => 'Perguruan Tinggi',
        ];

        return $labels[$this->jenis_institusi] ?? $this->jenis_institusi;
    }

    /**
     * Get kop surat path dari settings aplikasi berdasarkan jenis institusi
     */
    public function getKopSuratPathAttribute()
    {
        if ($this->jenis_institusi === 'yayasan') {
            return \App\Models\Setting::get('kop_surat_yayasan');
        }

        return \App\Models\Setting::get('kop_surat_pt');
    }

    // ═══════════════════════════════════════════════════════════
    // Methods
    // ═══════════════════════════════════════════════════════════

    /**
     * Generate format nomor surat untuk unit ini
     * Default format: {nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}
     * Custom format bisa diatur di prefix_format
     */
    public function getFormatNomorSurat(string $kodeJenis, int $nomor, int $bulan, int $tahun): string
    {
        // Jika ada custom format
        if ($this->prefix_format) {
            return str_replace(
                ['{nomor}', '{kode_jenis}', '{kode_unit}', '{bulan}', '{tahun}'],
                [
                    str_pad($nomor, 3, '0', STR_PAD_LEFT),
                    $kodeJenis,
                    $this->kode,
                    str_pad($bulan, 2, '0', STR_PAD_LEFT),
                    $tahun
                ],
                $this->prefix_format
            );
        }

        // Format default
        return sprintf(
            '%03d/%s/%s/%02d/%d',
            $nomor,
            $kodeJenis,
            $this->kode,
            $bulan,
            $tahun
        );
    }

    /**
     * Get kop surat path untuk PDF generation
     * Ambil dari settings aplikasi berdasarkan jenis institusi
     */
    public function getKopSuratForPdf(): ?string
    {
        if ($this->jenis_institusi === 'yayasan') {
            return \App\Models\Setting::get('kop_surat_yayasan');
        }

        return \App\Models\Setting::get('kop_surat_pt');
    }
}
