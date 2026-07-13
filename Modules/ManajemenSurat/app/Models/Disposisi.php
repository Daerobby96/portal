<?php

namespace Modules\ManajemenSurat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Disposisi extends Model
{
    protected $table = 'disposisi';

    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'kepada_user_id',
        'isi_disposisi',
        'batas_waktu',
        'prioritas',
        'status',
        'catatan_tindak_lanjut',
        'dibaca_at',
        'selesai_at',
    ];

    protected $casts = [
        'batas_waktu' => 'date',
        'dibaca_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    /**
     * Get the surat masuk
     */
    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    /**
     * Get the user who created the disposition (dari)
     */
    public function dari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    /**
     * Get the user who receives the disposition (kepada)
     */
    public function kepada(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }

    /**
     * Scope untuk filter berdasarkan penerima
     */
    public function scopeUntukUser($query, $userId)
    {
        return $query->where('kepada_user_id', $userId);
    }

    /**
     * Scope untuk disposisi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk disposisi yang sudah lewat deadline
     */
    public function scopeOverdue($query)
    {
        return $query->where('batas_waktu', '<', now())
                    ->whereNotIn('status', ['selesai']);
    }

    /**
     * Check if disposition is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->batas_waktu || $this->status === 'selesai') {
            return false;
        }
        
        return $this->batas_waktu->isPast();
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        if (!$this->dibaca_at) {
            $this->update([
                'dibaca_at' => now(),
                'status' => 'dibaca',
            ]);
        }
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(string $catatan = null): void
    {
        $this->update([
            'status' => 'selesai',
            'selesai_at' => now(),
            'catatan_tindak_lanjut' => $catatan ?? $this->catatan_tindak_lanjut,
        ]);
    }
}
