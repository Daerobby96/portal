<?php

namespace Modules\Sdm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;
use Modules\DataMaster\Models\UnitKerja;
use Modules\DataMaster\Models\Jabatan;

class Pegawai extends Model
{
    use Loggable;

    protected $table = 'pegawais';

    protected $fillable = [
        'nip', 'nama', 'email', 'no_hp',
        'jabatan', 'unit_kerja',
        'unit_kerja_id', 'jabatan_id',
        'jenis_pegawai', 'status_kepegawaian',
        'is_aktif', 'user_id',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    protected $appends = [
        'nama_unit_kerja',
        'nama_jabatan',
    ];

    // ── Constants ──────────────────────────────────────────────────
    const JENIS_DOSEN    = 'Dosen';
    const JENIS_TENDIK   = 'Tenaga Kependidikan';
    const JENIS_LAINNYA  = 'Lainnya';

    public static function jenisOptions(): array
    {
        return [
            self::JENIS_DOSEN   => 'Dosen',
            self::JENIS_TENDIK  => 'Tenaga Kependidikan',
            self::JENIS_LAINNYA => 'Lainnya',
        ];
    }

    public static function statusOptions(): array
    {
        return ['PNS', 'PPPK', 'Honorer', 'Kontrak', 'Tetap Yayasan', 'Lainnya'];
    }

    // ── Relationships ───────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unitKerjaRel(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function jabatanRel(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // ── Scopes ──────────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // ── Accessors ───────────────────────────────────────────────────
    public function getNamaUnitKerjaAttribute(): string
    {
        return $this->unitKerjaRel?->nama ?? $this->unit_kerja ?? '-';
    }

    public function getNamaJabatanAttribute(): string
    {
        return $this->jabatanRel?->nama ?? $this->jabatan ?? '-';
    }

    public function getJenisBadgeAttribute(): string
    {
        return match ($this->jenis_pegawai) {
            self::JENIS_DOSEN   => '<span class="badge bg-primary">Dosen</span>',
            self::JENIS_TENDIK  => '<span class="badge bg-info text-dark">Tendik</span>',
            default             => '<span class="badge bg-secondary">Lainnya</span>',
        };
    }

    public function getInisialAttribute(): string
    {
        $parts = explode(' ', trim($this->nama));
        $init  = strtoupper(substr($parts[0], 0, 1));
        if (count($parts) > 1) $init .= strtoupper(substr(end($parts), 0, 1));
        return $init;
    }

    /** Info singkat untuk search result */
    public function getInfoSingkatAttribute(): string
    {
        return implode(' · ', array_filter([
            $this->jenis_pegawai,
            $this->nama_jabatan,
            $this->nama_unit_kerja,
        ]));
    }
}
