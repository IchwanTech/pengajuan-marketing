<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanNasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nasabah_id',
        'jenis_pembiayaan',
        'nominal_pinjaman',
        'tenor',
        'status_pinjaman',
        'pinjaman_ke',
        'pinjaman_terakhir',
        'sisa_pinjaman',
        'realisasi_pinjaman',
        'cicilan_perbulan',
        'status_pengajuan',
        'validasi_pengajuan',
        'catatan',
        'berkas_jaminan',
        'catatan_spv',
        'catatan_marketing',
        'is_banding',
        'alasan_banding',
    ];

    public function nasabahLuar()
    {
        return $this->belongsTo(NasabahLuar::class, 'nasabah_id');
    }

    public function pengajuanSHM()
    {
        return $this->hasOne(PengajuanSHM::class, 'pengajuan_id');
    }

    public function pengajuanBPJS()
    {
        return $this->hasOne(PengajuanBPJS::class, 'pengajuan_id');
    }

    public function pengajuanKedinasan()
    {
        return $this->hasOne(PengajuanKedinasan::class, 'pengajuan_id');
    }

    public function pengajuanBpkb()
    {
        return $this->hasOne(PengajuanBpkb::class, 'pengajuan_id');
    }

    public function notifCA()
    {
        return $this->hasMany(NotificationCA::class, 'pengajuan_luar_id');
    }

    public function hasilSurvey()
    {
        return $this->hasOne(HasilSurveyPengajuan::class, 'pengajuan_id');
    }

    public function approval()
    {
        return $this->hasMany(ApprovalLuar::class, 'pengajuan_id');
    }
}
