<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alamat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'alamat_ktp',
        'rt_rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'status_rumah_ktp',
        'status_rumah',
        'domisili',
        'alamat_lengkap',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
