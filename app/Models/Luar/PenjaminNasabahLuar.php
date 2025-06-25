<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjaminNasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'hubungan_penjamin',
        'nama_penjamin',
        'pekerjaan_penjamin',
        'penghasilan_penjamin',
        'no_hp_penjamin',
        'persetujuan_penjamin',
        'foto_ktp_penjamin',
        'validasi_penjamin',
        'catatan'
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }
}
