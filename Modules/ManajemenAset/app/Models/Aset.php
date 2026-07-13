<?php

namespace Modules\ManajemenAset\Models;

use App\Models\User;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use SoftDeletes;

    protected $table = 'aset';

    protected $fillable = [
        'kategori_id', 'prodi_id', 'kode_aset', 'nama_aset', 'merk', 'tipe',
        'nomor_seri', 'kondisi', 'status', 'lokasi', 'ruangan',
        'tanggal_perolehan', 'sumber_perolehan', 'harga_perolehan',
        'umur_ekonomis', 'penanggung_jawab', 'spesifikasi', 'keterangan', 'foto'
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan' => 'decimal:2',
    ];

    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAset::class, 'kategori_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function pemeliharaans(): HasMany
    {
        return $this->hasMany(Pemeliharaan::class);
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Accessors
    public function getKondisiBadgeAttribute(): string
    {
        return match($this->kondisi) {
            'baik' => '<span class="badge bg-success">Baik</span>',
            'rusak_ringan' => '<span class="badge bg-warning">Rusak Ringan</span>',
            'rusak_berat' => '<span class="badge bg-danger">Rusak Berat</span>',
            'hilang' => '<span class="badge bg-dark">Hilang</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'aktif' => '<span class="badge bg-success">Aktif</span>',
            'non_aktif' => '<span class="badge bg-secondary">Non Aktif</span>',
            'dalam_perbaikan' => '<span class="badge bg-warning">Dalam Perbaikan</span>',
            'dihapuskan' => '<span class="badge bg-danger">Dihapuskan</span>',
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
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    public function scopeByProdi($query, $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }
}
