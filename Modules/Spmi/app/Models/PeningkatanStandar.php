<?php

namespace Modules\Spmi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\DataMaster\Models\Periode;
use App\Models\User;

class PeningkatanStandar extends Model
{
    protected $table = 'peningkatan_standars';

    protected $fillable = [
        'periode_id',
        'standar_id',
        'indikator_kinerja_id',
        'target_lama',
        'capaian_saat_ini',
        'target_baru',
        'dasar_pertimbangan',
        'strategi_pencapaian',
        'status',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_persetujuan' => 'date',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function standar(): BelongsTo
    {
        return $this->belongsTo(Standar::class, 'standar_id');
    }

    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class, 'indikator_kinerja_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
