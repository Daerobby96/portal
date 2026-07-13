<?php

namespace Modules\Tridharma\Models;

use Modules\Sdm\Models\Pegawai;

use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'pegawai_id', 'judul_hki', 'jenis_hki', 'nomor_pencatatan',
        'tahun_terbit', 'status', 'sertifikat', 'keterangan'
    ];

    public const JENIS_HKI = [
        'Paten',
        'Paten Sederhana',
        'Hak Cipta',
        'Merek',
        'Desain Industri',
        'Lainnya'
    ];

    public const STATUS = [
        'Terdaftar',
        'Granted/Sertifikat'
    ];

    public function pegawai()
    {
        return $this->belongsTo(\Modules\Sdm\Models\Pegawai::class, 'pegawai_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Granted/Sertifikat' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Granted</span>',
            'Terdaftar'          => '<span class="badge bg-info"><i class="bi bi-hourglass-split me-1"></i>Terdaftar</span>',
            default              => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }
}

