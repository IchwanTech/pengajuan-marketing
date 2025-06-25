<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanSHM extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'atas_nama_shm',
        'hubungan_shm',
        'alamat_shm',
        'luas_shm',
        'njop_shm',
        'foto_shm',
        'foto_kk_pemilik_shm',
        'foto_pbb',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_id');
    }
}
