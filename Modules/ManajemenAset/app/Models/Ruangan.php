<?php

namespace Modules\ManajemenAset\Models;

use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    
    protected $fillable = [
        'prodi_id', 'kode_ruangan', 'nama_ruangan', 'jenis', 'gedung', 'lantai',
        'kapasitas', 'luas', 'kondisi', 'status', 'ber_ac', 'ber_proyektor',
        'penanggung_jawab', 'fasilitas', 'keterangan', 'foto'
    ];

    protected $casts = [
        'luas' => 'decimal:2',
        'ber_ac' => 'boolean',
        'ber_proyektor' => 'boolean',
    ];

    // Relationships
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingRuangan::class, 'ruangan_id');
    }

    // Accessors
    public function getJenisBadgeAttribute(): string
    {
        return match($this->jenis) {
            'kelas' => '<span class="badge bg-primary">Kelas</span>',
            'lab' => '<span class="badge bg-info">Lab</span>',
            'ruang_rapat' => '<span class="badge bg-success">Ruang Rapat</span>',
            'ruang_dosen' => '<span class="badge bg-warning">Ruang Dosen</span>',
            'perpustakaan' => '<span class="badge bg-secondary">Perpustakaan</span>',
            'lainnya' => '<span class="badge bg-dark">Lainnya</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match($this->kondisi) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'rusak_ringan' => '<span class="badge bg-warning">Rusak Ringan</span>',
            'rusak_berat' => '<span class="badge bg-danger">Rusak Berat</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'tersedia' => '<span class="badge bg-success">Tersedia</span>',
            'tidak_tersedia' => '<span class="badge bg-secondary">Tidak Tersedia</span>',
            'dalam_perbaikan' => '<span class="badge bg-warning">Dalam Perbaikan</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto 
            ? asset('storage/' . $this->foto)
            : asset('images/no-image.png');
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
}
