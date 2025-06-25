<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluarga extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_nasabah',
        'hubungan',
        'nama',
        'bekerja',
        'nama_perusahaan',
        'jabatan',
        'penghasilan',
        'alamat_kerja',
        'no_hp',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
