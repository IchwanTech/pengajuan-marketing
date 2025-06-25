<?php

namespace App\Models\Luar;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalLuar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'role',
        'analisa',
        'nominal_pinjaman',
        'tenor',
        'status',
        'catatan',
        'is_banding'
    ];

    public function pengajuanLuar()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
