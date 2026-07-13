<?php

namespace Modules\Sdm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Traits\Loggable;

class PenilaianKinerja extends Model
{
    use Loggable;

    protected $table = 'penilaian_kinerja';

    protected $fillable = [
        'pegawai_id', 'tahun', 'periode',
        'nilai_disiplin', 'nilai_kinerja', 'nilai_loyalitas',
        'nilai_kreativitas', 'nilai_kerjasama', 'nilai_total', 'predikat',
        'catatan_atasan', 'catatan_pegawai', 'file_dokumen',
        'penilai_id', 'status', 'submitted_at',
    ];

    protected $casts = [
        'nilai_disiplin' => 'decimal:2',
        'nilai_kinerja' => 'decimal:2',
        'nilai_loyalitas' => 'decimal:2',
        'nilai_kreativitas' => 'decimal:2',
        'nilai_kerjasama' => 'decimal:2',
        'nilai_total' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    public static function periodeOptions(): array
    {
        return [
            'semester_1' => 'Semester 1',
            'semester_2' => 'Semester 2',
            'tahunan' => 'Tahunan',
        ];
    }

    public static function predikatOptions(): array
    {
        return [
            'sangat_baik' => 'Sangat Baik (≥90)',
            'baik' => 'Baik (80-89)',
            'cukup' => 'Cukup (70-79)',
            'kurang' => 'Kurang (<70)',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'verified' => 'Terverifikasi',
        ];
    }

    // Relationships
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    // Accessors
    public function getPredikatBadgeAttribute(): string
    {
        return match ($this->predikat) {
            'sangat_baik' => '<span class="badge bg-success">Sangat Baik</span>',
            'baik' => '<span class="badge bg-primary">Baik</span>',
            'cukup' => '<span class="badge bg-warning">Cukup</span>',
            'kurang' => '<span class="badge bg-danger">Kurang</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'submitted' => '<span class="badge bg-warning">Diajukan</span>',
            'verified' => '<span class="badge bg-success">Terverifikasi</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }

    // Auto calculate total and predikat
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate total
            $model->nilai_total = (
                $model->nilai_disiplin +
                $model->nilai_kinerja +
                $model->nilai_loyalitas +
                $model->nilai_kreativitas +
                $model->nilai_kerjasama
            ) / 5;

            // Determine predikat
            $model->predikat = match (true) {
                $model->nilai_total >= 90 => 'sangat_baik',
                $model->nilai_total >= 80 => 'baik',
                $model->nilai_total >= 70 => 'cukup',
                default => 'kurang',
            };
        });
    }
}
