<?php

namespace Modules\ManajemenRapat\Models;

use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Modules\Sdm\Models\Pegawai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RapatTindakLanjut extends Model
{
    protected $table = 'rapat_tindak_lanjuts';

    protected $fillable = [
        'rapat_id', 'pic_id', 'deskripsi', 'deadline', 'prioritas',
        'status', 'catatan_progres', 'tanggal_selesai_aktual', 'completed_by',
    ];

    protected $casts = [
        'deadline'               => 'date',
        'tanggal_selesai_aktual' => 'date',
    ];

    const STATUS_BELUM_MULAI  = 'belum_mulai';
    const STATUS_DALAM_PROSES = 'dalam_proses';
    const STATUS_SELESAI      = 'selesai';
    const STATUS_DIBATALKAN   = 'dibatalkan';

    const PRIORITAS_TINGGI  = 'Tinggi';
    const PRIORITAS_SEDANG  = 'Sedang';
    const PRIORITAS_RENDAH  = 'Rendah';

    public function rapat(): BelongsTo
    {
        return $this->belongsTo(Rapat::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function getIsOverdueAttribute(): bool
    {
        return !in_array($this->status, [self::STATUS_SELESAI, self::STATUS_DIBATALKAN])
            && $this->deadline->isPast();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_BELUM_MULAI  => '<span class="badge bg-secondary">Belum Mulai</span>',
            self::STATUS_DALAM_PROSES => '<span class="badge bg-primary">Dalam Proses</span>',
            self::STATUS_SELESAI      => '<span class="badge bg-success">Selesai</span>',
            self::STATUS_DIBATALKAN   => '<span class="badge bg-danger">Dibatalkan</span>',
            default                   => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getPrioritasBadgeAttribute(): string
    {
        return match ($this->prioritas) {
            self::PRIORITAS_TINGGI => '<span class="badge bg-danger">Tinggi</span>',
            self::PRIORITAS_SEDANG => '<span class="badge bg-warning text-dark">Sedang</span>',
            self::PRIORITAS_RENDAH => '<span class="badge bg-success">Rendah</span>',
            default                => '<span class="badge bg-secondary">-</span>',
        };
    }
}


