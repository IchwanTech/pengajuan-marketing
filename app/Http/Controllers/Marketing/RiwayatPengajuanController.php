<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPengajuanController extends Controller
{
    public function index()
    {
        return view('marketing.riwayat-pengajuan');
    }
}
