<?php

namespace Modules\Sdm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Traits\Loggable;

class Presensi extends Model
{
    use Loggable;

    protected $table = 'presensi';

    protected $fillable = [
        'pegawai_id', 'tanggal', 'jam_masuk', 'jam_keluar',
        'lokasi_masuk', 'lokasi_keluar', 'status', 'keterangan',
        'foto_masuk', 'foto_keluar',
        'latitude_masuk', 'longitude_masuk',
        'latitude_keluar', 'longitude_keluar',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
        'latitude_masuk' => 'decimal:7',
        'longitude_masuk' => 'decimal:7',
        'latitude_keluar' => 'decimal:7',
        'longitude_keluar' => 'decimal:7',
    ];

    public static function statusOptions(): array
    {
        return [
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpa' => 'Alpa',
            'cuti' => 'Cuti',
            'dinas_luar' => 'Dinas Luar',
        ];
    }

    // Relationships
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'hadir' => '<span class="badge bg-success">Hadir</span>',
            'izin' => '<span class="badge bg-warning">Izin</span>',
            'sakit' => '<span class="badge bg-info">Sakit</span>',
            'alpa' => '<span class="badge bg-danger">Alpa</span>',
            'cuti' => '<span class="badge bg-primary">Cuti</span>',
            'dinas_luar' => '<span class="badge bg-secondary">Dinas Luar</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }

    public function getDurasiKerjaAttribute(): ?string
    {
        if (!$this->jam_masuk || !$this->jam_keluar) {
            return null;
        }

        $masuk = \Carbon\Carbon::parse($this->jam_masuk);
        $keluar = \Carbon\Carbon::parse($this->jam_keluar);
        
        $diff = $masuk->diff($keluar);
        return sprintf('%d jam %d menit', $diff->h, $diff->i);
    }
}
