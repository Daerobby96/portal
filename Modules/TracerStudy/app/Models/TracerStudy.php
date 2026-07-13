<?php

namespace Modules\TracerStudy\Models;

use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    protected $table = 'tracer_studies';

    protected $fillable = [
        'nim', 'nama', 'prodi', 'tahun_lulus', 'telepon', 'email',
        'status_kerja', 'perusahaan', 'jabatan', 'gaji', 'waktu_tunggu_bulan',
        'tingkat_instansi', 'keselarasan_horisontal', 'keselarasan_vertikal',
        'raw_data'
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];
}
