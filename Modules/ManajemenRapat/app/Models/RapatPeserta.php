<?php

namespace Modules\ManajemenRapat\Models;

use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapatPeserta extends Model
{
    protected $table = 'rapat_peserta';

    protected $fillable = [
        'rapat_id', 'user_id', 'pegawai_id',
        'nama_eksternal', 'instansi', 'jabatan_eksternal',
        'email_eksternal', 'no_hp_eksternal',
        'peran', 'keterangan',
        'status_kehadiran', 'kehadiran_updated_at',
    ];

    protected $casts = [
        'kehadiran_updated_at' => 'datetime',
    ];

    // ── Constants ──────────────────────────────────────────────────
    const STATUS_DIUNDANG    = 'diundang';
    const STATUS_HADIR       = 'hadir';
    const STATUS_TIDAK_HADIR = 'tidak_hadir';
    const STATUS_IZIN        = 'izin';

    const TIPE_INTERNAL  = 'internal';
    const TIPE_EKSTERNAL = 'eksternal';

    // ── Relationships ───────────────────────────────────────────────
    public function rapat(): BelongsTo
    {
        return $this->belongsTo(Rapat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────

    /** Apakah peserta eksternal (bukan user sistem dan bukan pegawai) */
    public function isEksternal(): bool
    {
        return is_null($this->user_id) && is_null($this->pegawai_id);
    }

    /** Nama tampilan — dari user, pegawai, atau nama_eksternal */
    public function getNamaDisplayAttribute(): string
    {
        if ($this->user_id && $this->user) {
            return $this->user->name;
        }
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->nama;
        }
        return $this->nama_eksternal ?? 'Peserta Tidak Dikenal';
    }

    /** Instansi / unit kerja tampilan */
    public function getInstansiDisplayAttribute(): string
    {
        if ($this->user_id && $this->user) {
            return $this->user->unit_kerja ?? $this->user->jabatan ?? '';
        }
        if ($this->pegawai_id && $this->pegawai) {
            return implode(', ', array_filter([
                $this->pegawai->unit_kerja,
                $this->pegawai->jabatan,
            ])) ?: 'Pegawai';
        }
        return implode(', ', array_filter([
            $this->instansi,
            $this->jabatan_eksternal,
        ]));
    }

    /** Inisial untuk avatar */
    public function getInisialAttribute(): string
    {
        return strtoupper(substr($this->nama_display, 0, 1));
    }

    /** Warna avatar berdasarkan tipe */
    public function getAvatarColorAttribute(): string
    {
        if ($this->user_id) {
            return 'primary';
        }
        if ($this->pegawai_id) {
            return 'info';
        }
        return 'secondary';
    }

    // ── Accessors ───────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_kehadiran) {
            self::STATUS_HADIR       => '<span class="badge bg-success">Hadir</span>',
            self::STATUS_TIDAK_HADIR => '<span class="badge bg-danger">Tidak Hadir</span>',
            self::STATUS_IZIN        => '<span class="badge bg-warning text-dark">Izin</span>',
            default                  => '<span class="badge bg-secondary">Diundang</span>',
        };
    }

    public function getTipeBadgeAttribute(): string
    {
        if ($this->user_id) {
            return '<span class="badge bg-light text-dark border">Internal</span>';
        }
        if ($this->pegawai_id) {
            return '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">Pegawai</span>';
        }
        return '<span class="badge bg-info text-dark">Eksternal</span>';
    }

    public function getEmailDisplayAttribute(): ?string
    {
        if ($this->user_id && $this->user) {
            return $this->user->email;
        }
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->email;
        }
        return $this->email_eksternal;
    }

    public function getNoHpDisplayAttribute(): ?string
    {
        if ($this->user_id && $this->user) {
            return $this->user->no_hp ?? null;
        }
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->no_hp;
        }
        return $this->no_hp_eksternal;
    }
}


