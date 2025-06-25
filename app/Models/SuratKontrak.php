<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKontrak extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kontrak',
        'nama',
        'alamat',
        'no_ktp',
        'type',
        'inisial_marketing',
        'golongan',
        'perusahaan',
        'inisial_ca',
        'id_card',
        'kedinasan',
        'pinjaman_ke',
        'pokok_pinjaman',
        'tenor',
        'biaya_admin',
        'cicilan',
        'biaya_layanan',
        'bunga',
        'tanggal_jatuh_tempo',
        'nomor_urut',
        'kelompok',
        'catatan'
    ];
}
