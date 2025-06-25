<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'kode_voucher',
        'kadaluarsa',
        'voucher',
        'type',
        'saldo',
    ];
}
