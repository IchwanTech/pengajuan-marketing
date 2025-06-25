<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kerabat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'kerabat_kerja',
        'nama',
        'alamat',
        'no_hp',
        'status_hubungan',
        'nama_perusahaan',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
