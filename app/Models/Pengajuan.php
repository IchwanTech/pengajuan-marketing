<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengajuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'status_pinjaman',
        'pinjaman_ke',
        'nominal_pinjaman',
        'tenor',
        'keperluan',
        'status',
        'riwayat_nominal',
        'riwayat_tenor',
        'sisa_pinjaman',
        'notes',
        'is_banding',
        'alasan_banding'
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function approval()
    {
        return $this->hasMany(Approval::class, 'pengajuan_id');
    }

    public function notifikasiMarketing()
    {
        return $this->hasMany(NotifactionMarketing::class);
    }

    public function notifikasiSupervisor()
    {
        return $this->hasMany(NotifactionSupervisor::class);
    }

    public function notifikasiCreditAnalyst()
    {
        return $this->hasMany(NotifactionCreditAnalyst::class);
    }

    public function NotifikasiHead()
    {
        return $this->hasMany(NotifactionHeadMarketing::class);
    }
}
