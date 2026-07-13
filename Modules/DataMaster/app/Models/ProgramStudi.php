<?php

namespace Modules\DataMaster\Models;

use App\Models\User;
use Modules\Spmi\Models\Standar;
use Modules\Spmi\Models\IndikatorKinerja;
use Modules\Spmi\Models\Monitoring;
use App\Models\Audit;
use App\Models\Dokumen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStudi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode', 'nama', 'jenjang', 'akreditasi', 'is_aktif', 'deskripsi',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    const JENJANG = ['D3', 'D4', 'S1', 'S2', 'S3', 'Profesi'];

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'prodi_id');
    }

    public function standars(): HasMany
    {
        return $this->hasMany(Standar::class, 'prodi_id');
    }

    public function indikators(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'prodi_id');
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class, 'prodi_id');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'prodi_id');
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'prodi_id');
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getJenjangBadgeAttribute(): string
    {
        $colors = [
            'D3'      => 'bg-secondary',
            'D4'      => 'bg-info text-dark',
            'S1'      => 'bg-primary',
            'S2'      => 'bg-warning text-dark',
            'S3'      => 'bg-danger',
            'Profesi' => 'bg-dark',
        ];
        $color = $colors[$this->jenjang] ?? 'bg-secondary';
        return "<span class=\"badge {$color}\">{$this->jenjang}</span>";
    }

    public function getAkreditasiBadgeAttribute(): string
    {
        if (!$this->akreditasi) return '<span class="badge bg-light text-muted">-</span>';

        $colors = [
            'Unggul'      => 'bg-success',
            'A'           => 'bg-success',
            'Baik Sekali' => 'bg-primary',
            'B'           => 'bg-primary',
            'Baik'        => 'bg-info text-dark',
            'C'           => 'bg-warning text-dark',
        ];
        $color = $colors[$this->akreditasi] ?? 'bg-secondary';
        return "<span class=\"badge {$color}\">{$this->akreditasi}</span>";
    }

    // ─── Helper Statistik ──────────────────────────────────────────
    public function getStatistik(?int $periodeId = null): array
    {
        $periode = $periodeId ? Periode::find($periodeId) : Periode::aktif();

        return [
            'total_user'       => $this->users()->count(),
            'total_indikator'  => $this->indikators()->where('is_aktif', true)->count(),
            'total_standar'    => $this->standars()->count(),
            'total_monitoring' => $this->monitorings()
                ->when($periode, fn($q) => $q->where('periode_id', $periode->id))
                ->count(),
            'total_audit'      => $this->audits()
                ->when($periode, fn($q) => $q->where('periode_id', $periode->id))
                ->count(),
            'total_dokumen'    => $this->dokumens()->where('status', 'approved')->count(),
        ];
    }
}
