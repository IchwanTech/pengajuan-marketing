<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PinjamanLainNasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pinjaman_lain_nasabah_luars';

    protected $fillable = [
        'nasabah_id',
        'cicilan_lain',
        'nama_pembiayaan',
        'total_pinjaman',
        'cicilan_perbulan',
        'sisa_tenor',
        'validasi_pinjaman_lain',
        'catatan'
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }
}
