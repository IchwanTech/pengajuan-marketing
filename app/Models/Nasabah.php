<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nasabah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
        'marketing_id',
        'nama_lengkap',
        'no_ktp',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'email',
        'status_nikah',
        'enable_edit',
        'foto_ktp',
        'foto_kk',
        'foto_id_card',
        'foto_rekening',
        'no_rekening',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function alamat()
    {
        return $this->hasOne(Alamat::class);
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class);
    }

    public function kerabat()
    {
        return $this->hasOne(Kerabat::class);
    }

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'nasabah_id', 'id');
    }

    public function pengajuan()
    {
        return $this->hasOne(Pengajuan::class);
    }

    public function jaminan()
    {
        return $this->hasOne(Jaminan::class);
    }
}
