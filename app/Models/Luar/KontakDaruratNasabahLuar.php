<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KontakDaruratNasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'nama_kontak_darurat',
        'hubungan_kontak_darurat',
        'no_hp_kontak_darurat',
        'validasi_kontak_darurat',
        'catatan'
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }
}
