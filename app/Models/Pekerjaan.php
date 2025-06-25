<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pekerjaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'perusahaan',
        'divisi',
        'lama_kerja_tahun',
        'lama_kerja_bulan',
        'golongan',
        'yayasan',
        'nama_atasan',
        'nama_hrd',
        'absensi',
        'bukti_absensi',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
