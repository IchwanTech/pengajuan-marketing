<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSPV extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_luar_id',
        'pesan',
        'read'
    ];

    public function pengajuanLuar()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_luar_id');
    }
}
