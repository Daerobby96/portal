<?php

namespace Modules\ManajemenRapat\Models;

use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Modules\Sdm\Models\Pegawai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Loggable;

class Rapat extends Model
{
    use Loggable;

    protected $table = 'rapats';

    protected $fillable = [
        'periode_id', 'created_by', 'judul', 'jenis', 'tanggal',
        'waktu_mulai', 'waktu_selesai', 'tempat', 'deskripsi', 'kesimpulan',
        'status', 'alasan_pembatalan',
        'input_audit_internal', 'input_umpan_balik', 'input_kinerja_proses',
        'input_status_tindakan', 'input_perubahan_sistem', 'input_rekomendasi',
        'output_keefektifan', 'output_perbaikan', 'output_sumber_daya',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Constants ──────────────────────────────────────────────────
    const STATUS_DRAFT       = 'draft';
    const STATUS_TERJADWAL   = 'terjadwal';
    const STATUS_BERLANGSUNG = 'berlangsung';
    const STATUS_SELESAI     = 'selesai';
    const STATUS_DIBATALKAN  = 'dibatalkan';

    const JENIS_RTM        = 'RTM';
    const JENIS_KOORDINASI = 'Koordinasi';
    const JENIS_EVALUASI   = 'Evaluasi';
    const JENIS_AUDIT      = 'Audit';
    const JENIS_KHUSUS     = 'Khusus';

    public static function jenisOptions(): array
    {
        return [
            self::JENIS_RTM        => 'Rapat Tinjauan Manajemen (RTM)',
            self::JENIS_KOORDINASI => 'Rapat Koordinasi',
            self::JENIS_EVALUASI   => 'Rapat Evaluasi',
            self::JENIS_AUDIT      => 'Rapat Audit',
            self::JENIS_KHUSUS     => 'Rapat Khusus',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT       => 'Draft',
            self::STATUS_TERJADWAL   => 'Terjadwal',
            self::STATUS_BERLANGSUNG => 'Berlangsung',
            self::STATUS_SELESAI     => 'Selesai',
            self::STATUS_DIBATALKAN  => 'Dibatalkan',
        ];
    }

    // ── Relationships ───────────────────────────────────────────────
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(RapatAgenda::class)->orderBy('urutan');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(RapatPeserta::class);
    }

    public function tindakLanjuts(): HasMany
    {
        return $this->hasMany(RapatTindakLanjut::class);
    }

    public function lampirans(): HasMany
    {
        return $this->hasMany(RapatLampiran::class);
    }

    // ── Accessors ───────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT       => '<span class="badge bg-secondary">Draft</span>',
            self::STATUS_TERJADWAL   => '<span class="badge bg-primary">Terjadwal</span>',
            self::STATUS_BERLANGSUNG => '<span class="badge bg-warning text-dark">Berlangsung</span>',
            self::STATUS_SELESAI     => '<span class="badge bg-success">Selesai</span>',
            self::STATUS_DIBATALKAN  => '<span class="badge bg-danger">Dibatalkan</span>',
            default                  => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT       => 'secondary',
            self::STATUS_TERJADWAL   => 'primary',
            self::STATUS_BERLANGSUNG => 'warning',
            self::STATUS_SELESAI     => 'success',
            self::STATUS_DIBATALKAN  => 'danger',
            default                  => 'light',
        };
    }

    public function getJenisBadgeAttribute(): string
    {
        $colors = [
            self::JENIS_RTM        => 'info',
            self::JENIS_KOORDINASI => 'primary',
            self::JENIS_EVALUASI   => 'warning',
            self::JENIS_AUDIT      => 'danger',
            self::JENIS_KHUSUS     => 'secondary',
        ];
        $color = $colors[$this->jenis] ?? 'secondary';
        return "<span class=\"badge bg-{$color}\">{$this->jenis}</span>";
    }

    public function getTotalDurasiAttribute(): int
    {
        return $this->agendas->sum('estimasi_durasi');
    }

    public function getJumlahPesertaAttribute(): int
    {
        return $this->peserta->count();
    }

    // ── Scopes ──────────────────────────────────────────────────────
    public function scopeByPeriode($query, $periodeId)
    {
        return $query->where('periode_id', $periodeId);
    }

    public function scopeTerjadwal($query)
    {
        return $query->where('status', self::STATUS_TERJADWAL);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    // ── Helpers ─────────────────────────────────────────────────────
    public function isEditableby(User $user): bool
    {
        return $user->hasAnyRole(["super_admin", "pimpinan"]);
    }

    public function isLocked(): bool
    {
        return in_array($this->status, [self::STATUS_SELESAI, self::STATUS_DIBATALKAN]);
    }

    public function hasUserAsPeserta(int $userId): bool
    {
        return $this->peserta->where('user_id', $userId)->isNotEmpty();
    }

    public function getJumlahPesertaInternalAttribute(): int
    {
        return $this->peserta->whereNotNull('user_id')->count();
    }

    public function getJumlahPesertaEksternalAttribute(): int
    {
        return $this->peserta->whereNull('user_id')->count();
    }
}


