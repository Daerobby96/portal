<?php

namespace Modules\ManajemenSurat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class SuratMasuk extends Model
{
    use SoftDeletes;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'jenis_surat_id',
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'pengirim',
        'alamat_pengirim',
        'perihal',
        'jumlah_lampiran',
        'keterangan_lampiran',
        'file_path',
        'sifat',
        'prioritas',
        'status',
        'catatan',
        'received_by',
        'created_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
        'jumlah_lampiran' => 'integer',
    ];

    /**
     * Get the jenis surat
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Get the receiver user
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get the creator user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get disposisi for this surat
     */
    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan sifat
     */
    public function scopeSifat($query, $sifat)
    {
        return $query->where('sifat', $sifat);
    }

    /**
     * Scope untuk filter berdasarkan prioritas
     */
    public function scopePrioritas($query, $prioritas)
    {
        return $query->where('prioritas', $prioritas);
    }

    /**
     * Scope untuk surat baru (belum diproses)
     */
    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    /**
     * Scope untuk surat urgent (sangat segera)
     */
    public function scopeUrgent($query)
    {
        return $query->where('sifat', 'sangat_segera');
    }

    /**
     * Check if surat has been dispositioned
     */
    public function hasDisposisi(): bool
    {
        return $this->disposisi()->exists();
    }
}
