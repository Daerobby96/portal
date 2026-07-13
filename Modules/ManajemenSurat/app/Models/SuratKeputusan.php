<?php

namespace Modules\SuratKeputusan\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SuratKeputusan extends Model
{
    protected $fillable = [
        'jenis_sk',
        'nomor_sk',
        'tentang',
        'isi_sk',
        'tanggal_ditetapkan',
        'penandatangan_nama',
        'penandatangan_jabatan',
        'file_path',
        'created_by'
    ];

    protected $casts = [
        'tanggal_ditetapkan' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
