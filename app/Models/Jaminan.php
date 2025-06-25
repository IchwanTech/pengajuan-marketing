<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jaminan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'jaminan_hrd',
        'jaminan_cg',
        'penjamin',
        'nama_penjamin',
        'lama_kerja_penjamin',
        'bagian',
        'absensi',
        'riwayat_pinjam_penjamin',
        'riwayat_nominal_penjamin',
        'riwayat_tenor_penjamin',
        'sisa_pinjaman_penjamin',
        'jaminan_cg_penjamin',
        'status_hubungan_penjamin',
        'foto_ktp_penjamin',
        'foto_id_card_penjamin',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
