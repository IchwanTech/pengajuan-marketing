<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationSurveyor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notification_surveyors';

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
