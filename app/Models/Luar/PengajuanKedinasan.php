<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanKedinasan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'instansi',
        'surat_permohonan_kredit',
        'surat_pernyataan_penjamin',
        'surat_persetujuan_pimpinan',
        'surat_keterangan_gaji',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_id');
    }
}
