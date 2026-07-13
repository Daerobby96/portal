<?php

namespace Modules\Kerjasama\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluasiMitra extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kerjasama_id',
        'tanggal_evaluasi',
        'nilai',
        'catatan',
        'evaluator_id',
    ];

    protected $casts = [
        'tanggal_evaluasi' => 'date',
        'nilai' => 'integer',
    ];

    public function kerjasama()
    {
        return $this->belongsTo(Kerjasama::class, 'kerjasama_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
