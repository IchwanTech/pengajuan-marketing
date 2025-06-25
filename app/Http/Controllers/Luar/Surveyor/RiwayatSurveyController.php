<?php

namespace App\Http\Controllers\Luar\Surveyor;

use App\Http\Controllers\Controller;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class RiwayatSurveyController extends Controller
{
    public function index()
    {
        $nasabah = PengajuanNasabahLuar::with('hasilSurvey')
            ->whereHas('hasilSurvey') // Hanya ambil data yang memiliki hasil survey
            ->get();
        return view('pengajuan-luar.surveyor.riwayat-hasil-survey', compact('nasabah'));
    }
}
