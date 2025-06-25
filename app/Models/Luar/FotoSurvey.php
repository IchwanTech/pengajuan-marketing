<?php

namespace App\Models\Luar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoSurvey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hasil_survey_id',
        'foto_survey'
    ];

    public function fotoHasilSurvey()
    {
        return $this->belongsTo(HasilSurveyPengajuan::class, 'hasil_survey_id');
    }
}
