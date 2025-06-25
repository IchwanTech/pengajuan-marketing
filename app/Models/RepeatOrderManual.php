<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepeatOrderManual extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'marketing_id',
        'nama_lengkap',
        'nik',
        'no_hp',
        'nominal_pinjaman',
        'tenor',
        'pinjaman_ke',
        'nama_marketing',
        'status_konsumen',
        'alasan_topup',
        'is_clear'
    ];

    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }
}
