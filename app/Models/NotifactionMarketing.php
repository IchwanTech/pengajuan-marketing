<?php

namespace App\Models;

use App\Models\Luar\PengajuanNasabahLuar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifactionMarketing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'pengajuan_luar_id',
        'pesan',
        'read',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function pengajuanLuar()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_luar_id');
    }
}
