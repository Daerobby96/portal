<?php

namespace Modules\Spmi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\DataMaster\Models\Periode;
use App\Models\User;

class Benchmarking extends Model
{
    protected $table = 'benchmarkings';

    protected $fillable = [
        'periode_id',
        'nama_mitra',
        'tingkat', // Lokal, Nasional, Internasional
        'bidang_standar',
        'tanggal_kegiatan',
        'capaian_institusi',
        'capaian_mitra',
        'gap_analisis',
        'best_practice_diadopsi',
        'rencana_tindak_lanjut',
        'status', // Perencanaan, Terlaksana, Diimplementasikan
        'pic_nama',
        'file_laporan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
}
