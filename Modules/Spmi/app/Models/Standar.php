<?php

namespace Modules\Spmi\Models;

use Modules\DataMaster\Models\ProgramStudi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Standar extends Model
{
    use \App\Traits\Loggable;

    protected $fillable = ['kode', 'nama', 'deskripsi', 'is_aktif', 'bidang', 'jenis', 'nomor', 'prodi_id'];

    protected $casts = [
        'is_aktif' => 'boolean',
        'nomor'    => 'integer',
    ];

    // ─── Konstanta Bidang ─────────────────────────────────────────
    const BIDANG_PENDIDIKAN   = 'pendidikan';
    const BIDANG_PENELITIAN   = 'penelitian';
    const BIDANG_PKM          = 'pkm';
    const BIDANG_INSTITUSIONAL = 'institusional';

    const JENIS_INTI     = 'inti';
    const JENIS_TAMBAHAN = 'tambahan';

    public static function bidangOptions(): array
    {
        return [
            self::BIDANG_PENDIDIKAN    => 'Standar Pendidikan',
            self::BIDANG_PENELITIAN    => 'Standar Penelitian',
            self::BIDANG_PKM           => 'Standar Pengabdian kepada Masyarakat (PkM)',
            self::BIDANG_INSTITUSIONAL => 'Standar Institusional',
        ];
    }

    public static function jenisOptions(): array
    {
        return [
            self::JENIS_INTI     => 'Inti — Wajib SN-Dikti (Permendikbud No.3/2020)',
            self::JENIS_TAMBAHAN => 'Tambahan — Standar Institusional',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopePendidikan($query)
    {
        return $query->where('bidang', self::BIDANG_PENDIDIKAN);
    }

    public function scopePenelitian($query)
    {
        return $query->where('bidang', self::BIDANG_PENELITIAN);
    }

    public function scopePkm($query)
    {
        return $query->where('bidang', self::BIDANG_PKM);
    }

    public function scopeInti($query)
    {
        return $query->where('jenis', self::JENIS_INTI);
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getBidangBadgeAttribute(): string
    {
        return match ($this->bidang) {
            self::BIDANG_PENDIDIKAN    => '<span class="badge bg-primary">Pendidikan</span>',
            self::BIDANG_PENELITIAN    => '<span class="badge bg-success">Penelitian</span>',
            self::BIDANG_PKM           => '<span class="badge bg-info text-dark">PkM</span>',
            self::BIDANG_INSTITUSIONAL => '<span class="badge bg-secondary">Institusional</span>',
            default                    => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getBidangLabelAttribute(): string
    {
        return match ($this->bidang) {
            self::BIDANG_PENDIDIKAN    => 'Pendidikan',
            self::BIDANG_PENELITIAN    => 'Penelitian',
            self::BIDANG_PKM           => 'PkM',
            self::BIDANG_INSTITUSIONAL => 'Institusional',
            default                    => '-',
        };
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    public function dokumens_many(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Dokumen::class, 'dokumen_standar');
    }

    public function indikators(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class);
    }

    public function prodi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
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
