<?php

namespace App\Models\Luar;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NasabahLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'marketing_id',
        'nama_lengkap',
        'nik',
        'no_ktp',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'email',
        'status_nikah',
        'foto_ktp',
        'foto_kk',
        'validasi_nasabah',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function marketing()
    {
        return $this->belongsTo(User::class, 'nasabah_id');
    }

    public function alamatLuar()
    {
        return $this->hasOne(AlamatNasabahLuar::class, 'nasabah_id');
    }

    public function pekerjaanLuar()
    {
        return $this->hasOne(PekerjaanNasabahLuar::class, 'nasabah_id');
    }

    public function penjaminLuar()
    {
        return $this->hasOne(PenjaminNasabahLuar::class, 'nasabah_id');
    }

    public function tanggunganLuar()
    {
        return $this->hasOne(TanggunganNasabahLuar::class, 'nasabah_id');
    }

    public function pinjamanLainLuar()
    {
        return $this->hasMany(PinjamanLainNasabahLuar::class, 'nasabah_id');
    }

    public function kontakDarurat()
    {
        return $this->hasOne(KontakDaruratNasabahLuar::class, 'nasabah_id');
    }

    public function pengajuanLuar()
    {
        return $this->hasMany(PengajuanNasabahLuar::class, 'nasabah_id');
    }
}
