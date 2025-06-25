<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PekerjaanNasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'perusahaan',
        'alamat_perusahaan',
        'kontak_perusahaan',
        'jabatan',
        'lama_kerja',
        'status_karyawan',
        'lama_kontrak',
        'pendapatan_perbulan',
        'slip_gaji',
        'norek',
        'id_card',
        'validasi_pekerjaan',
        'catatan'
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }
}
