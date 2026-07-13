<?php

namespace Modules\ManajemenSurat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'template_path',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get surat keluar with this jenis
     */
    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'jenis_surat_id');
    }

    /**
     * Get surat masuk with this jenis
     */
    public function suratMasuk(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'jenis_surat_id');
    }

    /**
     * Get nomor surat tracking
     */
    public function nomorSurat(): HasMany
    {
        return $this->hasMany(NomorSurat::class, 'jenis_surat_id');
    }

    /**
     * Scope untuk filter hanya jenis surat aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
