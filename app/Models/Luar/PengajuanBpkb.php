<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanBpkb extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'atas_nama_bpkb',
        'no_stnk',
        'alamat_pemilik_bpkb',
        'type_kendaraan',
        'warna_kendaraan',
        'tahun_perakitan',
        'stransmisi',
        'no_rangka',
        'no_mesin',
        'no_bpkb',
        'foto_stnk',
        'foto_kk_pemilik_bpkb',
        'foto_motor',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_id');
    }
}
