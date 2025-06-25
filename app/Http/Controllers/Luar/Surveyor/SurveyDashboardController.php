<?php

namespace App\Http\Controllers\Luar\Surveyor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyDashboardController extends Controller
{
    public function index()
    {
        return view('pengajuan-luar.surveyor.dashboard-surveyor');
    }
}
