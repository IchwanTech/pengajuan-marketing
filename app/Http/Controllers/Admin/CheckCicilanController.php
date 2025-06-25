<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataCicilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckCicilanController extends Controller
{
    public function index()
    {
        return view('check-cicilan');
    }

    public function checkCicilan(Request $request)
    {
        $request->validate([
            'id_pinjam' => 'required|string',
        ]);

        $id_pinjam = $request->id_pinjam;
        $cicilans = DataCicilan::where('id_pinjam', $id_pinjam)->get();

        if ($cicilans->isEmpty()) {
            return redirect()->back()->with(['error' => 'Data cicilan tidak ditemukan!']);
        }

        return redirect()->back()->with('cicilans', $cicilans);
    }
}
