<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlamatNasabahLuar extends Model
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
        'status_rumah',
        'biaya_perbulan',
        'biaya_pertahun',
        'domisili',
        'alamat_domisili',
        'rumah_domisili',
        'lama_tinggal',
        'biaya_perbulan_domisili',
        'biaya_pertahun_domisili',
        'atas_nama_listrik',
        'hubungan',
        'foto_meteran_listrik',
        'validasi_alamat',
        'catatan',
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }
}
