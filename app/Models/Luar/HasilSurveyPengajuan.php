<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilSurveyPengajuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pengajuan_id',
        'berjumpa_siapa',
        'hubungan',
        'status_rumah',
        'hasil_cekling1',
        'hasil_cekling2',
        'kesimpulan',
        'rekomendasi',
    ];

    public function pengajuanLuar()
    {
        return $this->belongsTo(PengajuanNasabahLuar::class, 'pengajuan_id');
    }

    public function fotoHasilSurvey()
    {
        return $this->hasMany(FotoSurvey::class, 'hasil_survey_id');
    }
}
