<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'mahasiswa_id', 'nama_kegiatan', 'jenis_prestasi', 'tingkat',
        'tahun', 'penyelenggara', 'peringkat', 'sertifikat', 'keterangan'
    ];

    public const JENIS_PRESTASI = [
        'Akademik',
        'Non-Akademik'
    ];

    public const TINGKAT = [
        'Lokal',
        'Nasional',
        'Internasional'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id');
    }

    public function getTingkatBadgeAttribute(): string
    {
        return match ($this->tingkat) {
            'Internasional' => '<span class="badge bg-warning text-dark"><i class="bi bi-globe-americas me-1"></i>Internasional</span>',
            'Nasional'      => '<span class="badge bg-info"><i class="bi bi-flag-fill me-1"></i>Nasional</span>',
            'Lokal'         => '<span class="badge bg-secondary"><i class="bi bi-geo-alt-fill me-1"></i>Lokal</span>',
            default         => '<span class="badge bg-light text-dark">' . $this->tingkat . '</span>',
        };
    }
}
