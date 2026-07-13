<?php

namespace Modules\ManajemenAset\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeliharaan extends Model
{
    protected $table = 'pemeliharaan';
    
    protected $fillable = [
        'aset_id', 'petugas_id', 'tanggal_pemeliharaan', 'jenis',
        'deskripsi_kegiatan', 'temuan', 'tindakan', 'hasil',
        'biaya', 'vendor', 'tanggal_berikutnya', 'bukti_foto'
    ];

    protected $casts = [
        'tanggal_pemeliharaan' => 'date',
        'tanggal_berikutnya' => 'date',
        'biaya' => 'decimal:2',
    ];

    // Relationships
    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Accessors
    public function getJenisBadgeAttribute(): string
    {
        return match($this->jenis) {
            'preventif' => '<span class="badge bg-primary">Preventif</span>',
            'korektif' => '<span class="badge bg-warning">Korektif</span>',
            'kalibrasi' => '<span class="badge bg-info">Kalibrasi</span>',
            'inspeksi' => '<span class="badge bg-secondary">Inspeksi</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getHasilBadgeAttribute(): string
    {
        return match($this->hasil) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'perlu_perbaikan' => '<span class="badge bg-warning">Perlu Perbaikan</span>',
            'perlu_penggantian' => '<span class="badge bg-danger">Perlu Penggantian</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getBuktiFotoUrlAttribute(): string
    {
        return $this->bukti_foto 
            ? asset('storage/' . $this->bukti_foto)
            : null;
    }
}
