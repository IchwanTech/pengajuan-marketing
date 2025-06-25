<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCicilan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pinjam',
        'nama_konsumen',
        'divisi',
        'tgl_pencairan',
        'pokok_pinjaman',
        'jumlah_tenor_seharusnya',
        'cicilan_perbulan',
        'pinjaman_ke',
        'sisa_tenor',
        'sisa_pinjaman',
    ];
}
