<?php

namespace Modules\Spmi\Models;

use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiklusSpmi extends Model
{
    protected $fillable = [
        'nama',
        'tahun_siklus',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
        'penanggung_jawab_id',
        'is_aktif',
        'snapshot_ppepp',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif'        => 'boolean',
        'snapshot_ppepp'  => 'array',
    ];

    public const STATUS = [
        'persiapan' => 'Persiapan',
        'berjalan'  => 'Berjalan',
        'evaluasi'  => 'Evaluasi Akhir',
        'ditutup'   => 'Ditutup',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function periodes(): HasMany
    {
        return $this->hasMany(Periode::class, 'siklus_spmi_id');
    }

    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public static function aktif(): ?self
    {
        return self::where('is_aktif', true)->first();
    }

    // ─── PPEPP Aggregate Computation ─────────────────────────────────

    /**
     * Compute PPEPP progress aggregated across ALL periodes in this cycle.
     */
    public function getPpeppAggregateAttribute(): array
    {
        // If cycle is closed, return snapshot
        if ($this->status === 'ditutup' && !empty($this->snapshot_ppepp)) {
            return $this->snapshot_ppepp;
        }

        $periodeIds = $this->periodes()->pluck('id')->toArray();

        if (empty($periodeIds)) {
            return $this->emptyPpepp();
        }

        $totalStandar   = Standar::count();
        $totalIndikator = IndikatorKinerja::where('is_aktif', true)->count();

        // P1 – Penetapan: Standar dengan minimal 1 dokumen approved
        $standarDenganDokumen = Standar::whereHas('dokumens', fn($q) => $q->where('status', 'approved'))->count();
        $penetapan = $totalStandar > 0 ? min(100, round(($standarDenganDokumen / $totalStandar) * 100)) : 0;

        // P2 – Pelaksanaan: Monitoring terisi di semua periode siklus ini
        $totalMonitoring = Monitoring::whereIn('periode_id', $periodeIds)->count();
        $pelaksanaan = $totalIndikator > 0 ? min(100, round(($totalMonitoring / ($totalIndikator * count($periodeIds))) * 100)) : 0;

        // P3 – Evaluasi: Audit selesai / total audit
        $totalAudit    = Audit::whereIn('periode_id', $periodeIds)->count();
        $selesaiAudit  = Audit::whereIn('periode_id', $periodeIds)->where('status', 'selesai')->count();
        $evaluasi      = $totalAudit > 0 ? min(100, round(($selesaiAudit / $totalAudit) * 100)) : 0;

        // P4 – Pengendalian: Temuan closed atau verified
        $totalTemuan    = Temuan::whereHas('audit', fn($q) => $q->whereIn('periode_id', $periodeIds))->count();
        $resolvedTemuan = Temuan::whereHas('audit', fn($q) => $q->whereIn('periode_id', $periodeIds))
            ->whereIn('status', ['closed', 'verified'])->count();
        $pengendalian = $totalTemuan > 0
            ? min(100, round(($resolvedTemuan / $totalTemuan) * 100))
            : ($evaluasi >= 100 ? 100 : 0);

        // P5 – Peningkatan: RTM selesai minimal 1 per periode
        $rtmSelesai   = RTM::whereIn('periode_id', $periodeIds)->where('status', 'selesai')->count();
        $peningkatan  = $rtmSelesai > 0 ? 100 : 0;

        $overall = round(($penetapan + $pelaksanaan + $evaluasi + $pengendalian + $peningkatan) / 5);

        return [
            'penetapan'    => $penetapan,
            'pelaksanaan'  => $pelaksanaan,
            'evaluasi'     => $evaluasi,
            'pengendalian' => $pengendalian,
            'peningkatan'  => $peningkatan,
            'overall'      => $overall,
            'is_loop_closed' => $overall >= 100,
            'stats' => [
                'total_standar'    => $totalStandar,
                'total_indikator'  => $totalIndikator,
                'total_audit'      => $totalAudit,
                'total_temuan'     => $totalTemuan,
                'resolved_temuan'  => $resolvedTemuan,
                'total_rtm'        => RTM::whereIn('periode_id', $periodeIds)->count(),
            ],
        ];
    }

    private function emptyPpepp(): array
    {
        return [
            'penetapan' => 0, 'pelaksanaan' => 0, 'evaluasi' => 0,
            'pengendalian' => 0, 'peningkatan' => 0, 'overall' => 0,
            'is_loop_closed' => false,
            'stats' => [],
        ];
    }

    // ─── Accessors / Badges ───────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'persiapan' => '<span class="badge bg-secondary">Persiapan</span>',
            'berjalan'  => '<span class="badge bg-primary">Berjalan</span>',
            'evaluasi'  => '<span class="badge bg-warning text-dark">Evaluasi Akhir</span>',
            'ditutup'   => '<span class="badge bg-dark">Ditutup</span>',
            default     => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'persiapan' => '#6c757d',
            'berjalan'  => '#4f46e5',
            'evaluasi'  => '#f59e0b',
            'ditutup'   => '#1e293b',
            default     => '#6c757d',
        ];
    }
}
