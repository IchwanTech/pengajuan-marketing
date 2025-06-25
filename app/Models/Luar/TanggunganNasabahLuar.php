<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TanggunganNasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'kondisi_tanggungan',
        'validasi_tanggungan',
        'catatan'
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }
}
