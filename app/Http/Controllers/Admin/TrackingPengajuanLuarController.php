<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\PengajuanNasabahLuar;
use Illuminate\Http\Request;

class TrackingPengajuanLuarController extends Controller
{
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');

        $pengajuan = NasabahLuar::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuanLuar', function ($query) use ($filterTime, $month, $year) {
                // $query->whereIn('status_pengajuan', ['aproved hm', 'rejected hm', 'approved banding hm', 'rejected banding hm']);
                // Filter berdasarkan waktu
                if ($filterTime == 'today') {
                    $query->whereDate('created_at', today());
                } elseif ($filterTime == 'this_month') {
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                } elseif ($filterTime == 'this_year') {
                    $query->whereYear('created_at', now()->year);
                }

                // Filter bulan tertentu
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }

                // Filter tahun tertentu
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            })
            ->with([
                'user',
                'pengajuanLuar'
                //  => function ($query) {
                //     $query->whereIn('status_pengajuan', ['aproved hm', 'rejected hm', 'approved banding hm', 'rejected banding hm'])
                //         ->orderBy('created_at', 'asc');
                // }
            ])
            ->get();

        return view('admin.data-pengajuan-luar', compact('pengajuan'));
    }

    public function show($id)
    {
        // $nasabah = NasabahLuar::with('alamatLuar', 'pekerjaanLuar', 'penjaminLuar', 'tanggunganLuar', 'pinjamanLainLuar', 'kontakDarurat', 'pengajuanLuar')->findOrFail($id);
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        $isSurvey = $pengajuan->hasilSurvey()->exists();
        // dd($nasabah);
        return view('admin.detail-pengajuan-luar', compact('pengajuan', 'isSurvey'));
    }
}
