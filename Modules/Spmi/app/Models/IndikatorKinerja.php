<?php

namespace Modules\Spmi\Models;

use Modules\DataMaster\Models\ProgramStudi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndikatorKinerja extends Model
{
    protected $fillable = [
        'kode', 'nama', 'unit_pengukuran', 'target_deskripsi',
        'target_nilai', 'unit_kerja', 'standar_id', 'is_aktif',
        'tipe', 'bobot', 'sumber', 'prodi_id',
    ];

    protected $casts = [
        'target_nilai' => 'decimal:2',
        'bobot'        => 'decimal:2',
        'is_aktif'     => 'boolean',
    ];

    // ─── Tipe Labels ──────────────────────────────────────────────
    const TIPE_IKU    = 'IKU';
    const TIPE_IKT    = 'IKT';
    const TIPE_CUSTOM = 'custom';

    public static function tipeOptions(): array
    {
        return [
            self::TIPE_IKU    => 'IKU — Indikator Kinerja Utama (Kemendikbud)',
            self::TIPE_IKT    => 'IKT — Indikator Kinerja Tambahan (Institusional)',
            self::TIPE_CUSTOM => 'Custom — Indikator Khusus',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeIku($query)
    {
        return $query->where('tipe', self::TIPE_IKU);
    }

    public function scopeIkt($query)
    {
        return $query->where('tipe', self::TIPE_IKT);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getTipeBadgeAttribute(): string
    {
        return match ($this->tipe) {
            self::TIPE_IKU    => '<span class="badge bg-danger">IKU</span>',
            self::TIPE_IKT    => '<span class="badge bg-warning text-dark">IKT</span>',
            self::TIPE_CUSTOM => '<span class="badge bg-secondary">Custom</span>',
            default           => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function standar(): BelongsTo
    {
        return $this->belongsTo(Standar::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class, 'indikator_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeForProdi($query, ?int $prodiId)
    {
        if ($prodiId === null) return $query;
        return $query->where(function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId)->orWhereNull('prodi_id');
        });
    }
}
