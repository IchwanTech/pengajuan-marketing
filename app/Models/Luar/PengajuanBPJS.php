<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanBPJS extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'saldo_bpjs',
        'tanggal_bayar_terakhir',
        'username',
        'password',
        'foto_bpjs',
        'foto_jaminan_lain',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_id');
    }
}
